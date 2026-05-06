<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $chatUsers = $this->getChatUsers($user);

        if (request()->ajax()) {
            return response()->json($chatUsers);
        }

        $layout = 'layouts.admin_premium';
        if ($user->role == 'umkm') $layout = 'layouts.umkm';
        if ($user->role == 'user') $layout = 'layouts.user';

        return view('chats.index_new', compact('chatUsers', 'layout'));
    }

    public function show(User $user)
    {
        $user->load('usaha');
        $currentUser = Auth::user();
        $chatUsers = $this->getChatUsers($currentUser, $user->id);
        
        $messages = Chat::where(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        // Note: Read status is now handled via AJAX in the frontend to avoid pre-fetching issues.

        if (request()->ajax()) {
            return response()->json([
                'user' => $user,
                'messages' => $messages
            ]);
        }

        $layout = 'layouts.admin_premium';
        if ($currentUser->role == 'umkm') $layout = 'layouts.umkm';
        if ($currentUser->role == 'user') $layout = 'layouts.user';

        // Custom display name if usaha_id is provided
        if (request()->has('usaha_id')) {
            $specificUsaha = \App\Models\Usaha::find(request('usaha_id'));
            if ($specificUsaha && $specificUsaha->user_id == $user->id) {
                $user->display_name = $specificUsaha->nama_usaha;
                $user->specific_usaha = $specificUsaha;
            }
        }
        
        if (!isset($user->display_name)) {
            $user->display_name = $user->usaha->nama_usaha ?? ($user->nama ?? $user->username);
        }

        return view('chats.show_new', compact('user', 'messages', 'chatUsers', 'layout'));
    }

    private function getChatUsers($user, $activeChatUserId = null)
    {
        // Fetch users who:
        // 1. Have already chatted with the current user OR
        // 2. Is the user we are currently chatting with (to allow starting new chats)
        return User::where('id', '!=', $user->id)
            ->where(function($query) use ($user, $activeChatUserId) {
                $query->whereHas('chatsSent', function($q) use ($user) {
                    $q->where('receiver_id', $user->id);
                })->orWhereHas('chatsReceived', function($q) use ($user) {
                    $q->where('sender_id', $user->id);
                });
                
                if ($activeChatUserId) {
                    $query->orWhere('id', $activeChatUserId);
                }
            })
            ->with(['usaha', 'chatsSent' => function($q) use ($user) {
                $q->where('receiver_id', $user->id)->latest();
            }, 'chatsReceived' => function($q) use ($user) {
                $q->where('sender_id', $user->id)->latest();
            }])
            ->get()
            ->map(function($u) use ($user) {
                $lastSent = $u->chatsSent->first();
                $lastReceived = $u->chatsReceived->first();
                
                $lastChat = collect([$lastSent, $lastReceived])->filter()->sortByDesc('created_at')->first();
                
                $u->display_name = $u->usaha->nama_usaha ?? ($u->nama ?? $u->username);
                $u->last_message = $lastChat ? $lastChat->message : '';
                $u->last_message_sender_id = $lastChat ? $lastChat->sender_id : null;
                $u->last_message_is_read = $lastChat ? $lastChat->is_read : false;
                $u->last_chat_time_raw = $lastChat ? $lastChat->created_at : null;
                $u->last_chat_time = $lastChat ? $lastChat->created_at->format('H:i') : '';
                $u->unread_count = Chat::where('sender_id', $u->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();
                return $u;
            })
            ->sort(function($a, $b) {
                if ($a->unread_count != $b->unread_count) {
                    return $b->unread_count <=> $a->unread_count;
                }
                if ($a->last_chat_time_raw != $b->last_chat_time_raw) {
                    return $b->last_chat_time_raw <=> $a->last_chat_time_raw;
                }
                return strcasecmp($a->display_name, $b->display_name);
            })->values();
    }

    public function store(Request $request)
    {
        \Log::info('Chat Store Request:', $request->all());

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,docx,doc,xls,xlsx,ppt,pptx,txt,zip',
            'reply_to_id' => 'nullable|exists:chats,id',
        ]);

        $type = 'text';
        $attachmentPath = null;
        $messageContent = $request->input('message');

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'file';
            $attachmentPath = $file->store('chat_attachments', 'public');
        }

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $messageContent,
            'type' => $type,
            'attachment' => $attachmentPath,
            'reply_to_id' => $request->reply_to_id,
        ]);

        \Log::info('Chat Created:', $chat->toArray());

        $chat->load('replyTo');

        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        if ($request->ajax()) {
            return response()->json($chat);
        }

        return back()->with('success', 'Pesan terkirim.');
    }

    public function update(Request $request, Chat $chat)
    {
        if ($chat->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $chat->update([
                'message' => $request->message,
                'is_edited' => true,
            ]);

            $chat->load(['sender', 'receiver']);

            broadcast(new \App\Events\MessageUpdated($chat))->toOthers();

            return response()->json($chat);
        } catch (\Exception $e) {
            \Log::error("Chat Update Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function deleteForMe(Chat $chat)
    {
        $user = Auth::user();
        if ($chat->sender_id == $user->id) {
            $chat->update(['deleted_by_sender' => true]);
        } else if ($chat->receiver_id == $user->id) {
            $chat->update(['deleted_by_receiver' => true]);
        }

        broadcast(new \App\Events\MessageDeleted($chat->id, $chat->sender_id, $chat->receiver_id, 'me'));

        return response()->json(['success' => true]);
    }

    public function deleteForEveryone(Chat $chat)
    {
        $this->authorize('delete', $chat);

        $senderId = $chat->sender_id;
        $receiverId = $chat->receiver_id;
        $chatId = $chat->id;

        $chat->delete();

        broadcast(new \App\Events\MessageDeleted($chatId, $senderId, $receiverId, 'everyone'));

        return response()->json(['success' => true]);
    }

    public function markAsRead(User $user)
    {
        $currentUser = Auth::user();
        \Log::info("Chat: User {$currentUser->id} marking messages from {$user->id} as READ");
        $unreadMessages = Chat::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $msg) {
            $msg->update(['is_read' => true, 'is_delivered' => true]);
            broadcast(new \App\Events\MessageRead($msg))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }
    public function markAsDelivered(User $user)
    {
        $currentUser = Auth::user();
        \Log::info("Chat: User {$currentUser->id} marking messages from {$user->id} as DELIVERED");
        $undeliveredMessages = Chat::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_delivered', false)
            ->get();

        foreach ($undeliveredMessages as $msg) {
            $msg->update(['is_delivered' => true]);
            broadcast(new \App\Events\MessageDelivered($msg))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    public function markAllAsDelivered()
    {
        $currentUser = Auth::user();
        $undeliveredMessages = Chat::where('receiver_id', $currentUser->id)
            ->where('is_delivered', false)
            ->get();

        foreach ($undeliveredMessages as $msg) {
            $msg->update(['is_delivered' => true]);
            broadcast(new \App\Events\MessageDelivered($msg))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }
}
