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
        $usahaId = request('usaha_id');
        $user->load('usaha');
        $currentUser = Auth::user();
        
        // Pass usahaId to getChatUsers to ensure the sidebar reflects the current shop
        $chatUsers = $this->getChatUsers($currentUser, $user->id, $usahaId);
        
        $messages = Chat::where(function($q) use ($currentUser, $user, $usahaId) {
            $q->where('sender_id', $currentUser->id)
              ->where('receiver_id', $user->id)
              ->where('usaha_id', $usahaId);
        })->orWhere(function($q) use ($currentUser, $user, $usahaId) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $currentUser->id)
              ->where('usaha_id', $usahaId);
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
        if ($usahaId) {
            $specificUsaha = \App\Models\Usaha::find($usahaId);
            if ($specificUsaha && $specificUsaha->user_id == $user->id) {
                $user->display_name = $specificUsaha->nama_usaha;
                $user->specific_usaha = $specificUsaha;
            }
        }
        
        if (!isset($user->display_name)) {
            $user->display_name = $user->usaha->nama_usaha ?? ($user->nama ?? $user->username);
        }

        return view('chats.show_new', compact('user', 'messages', 'chatUsers', 'layout', 'usahaId'));
    }

    public function store(Request $request)
    {
        \Log::info('Chat Store Request:', $request->all());

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'usaha_id' => 'nullable|exists:usaha,id',
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
            'usaha_id' => $request->usaha_id,
            'message' => $messageContent,
            'type' => $type,
            'attachment' => $attachmentPath,
            'reply_to_id' => $request->reply_to_id,
        ]);

        \Log::info('Chat Created:', $chat->toArray());

        $chat->load(['replyTo', 'usaha']);

        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        if ($request->ajax()) {
            return response()->json($chat);
        }

        return back()->with('success', 'Pesan terkirim.');
    }

    private function getChatUsers($user, $activeChatUserId = null, $activeUsahaId = null)
    {
        // 1. Ambil semua kombinasi unik (partner_id, usaha_id) yang diikuti user ini
        $sent = Chat::where('sender_id', $user->id)
            ->select('receiver_id as partner_id', 'usaha_id');
            
        $received = Chat::where('receiver_id', $user->id)
            ->select('sender_id as partner_id', 'usaha_id');
            
        $combinations = $sent->union($received)->get();
        
        // 2. Jika ada chat aktif yang belum dimulai, tambahkan ke list
        if ($activeChatUserId) {
             $exists = $combinations->where('partner_id', $activeChatUserId)->where('usaha_id', $activeUsahaId)->first();
             if (!$exists) {
                 $combinations->push((object)['partner_id' => $activeChatUserId, 'usaha_id' => $activeUsahaId]);
             }
        }

        // 3. Petakan ke objek User dengan informasi tambahan
        return $combinations->map(function($combo) use ($user) {
            $u = User::with('usaha')->find($combo->partner_id);
            if (!$u) return null;
            
            // Clone user object to avoid sharing state if multiple usahas for same user
            $contact = clone $u;
            $contact->active_usaha_id = $combo->usaha_id;
            
            // Ambil pesan terakhir untuk pasangan (partner, usaha) spesifik ini
            $lastChat = Chat::where(function($q) use ($user, $combo) {
                $q->where('sender_id', $user->id)->where('receiver_id', $combo->partner_id)->where('usaha_id', $combo->usaha_id);
            })->orWhere(function($q) use ($user, $combo) {
                $q->where('sender_id', $combo->partner_id)->where('receiver_id', $user->id)->where('usaha_id', $combo->usaha_id);
            })->latest()->first();

            // Set nama tampilan berdasarkan role dan usaha_id
            if ($user->role === 'user' && $combo->usaha_id) {
                $usaha = \App\Models\Usaha::find($combo->usaha_id);
                $contact->display_name = $usaha ? $usaha->nama_usaha : ($u->nama ?? $u->username);
            } else {
                $contact->display_name = $u->nama ?? $u->username;
            }

            $contact->last_message = $lastChat ? $lastChat->message : '';
            $contact->last_message_sender_id = $lastChat ? $lastChat->sender_id : null;
            $contact->last_message_is_read = $lastChat ? $lastChat->is_read : false;
            $contact->last_chat_time_raw = $lastChat ? $lastChat->created_at : null;
            $contact->last_chat_time = $lastChat ? $lastChat->created_at->format('H:i') : '';
            
            $contact->unread_count = Chat::where('sender_id', $u->id)
                ->where('receiver_id', $user->id)
                ->where('usaha_id', $combo->usaha_id)
                ->where('is_read', false)
                ->count();
                
            return $contact;
        })->filter()->sortByDesc(function($u) {
            return $u->last_chat_time_raw;
        })->values();
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
