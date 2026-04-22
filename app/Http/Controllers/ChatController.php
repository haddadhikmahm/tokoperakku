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

        return view('chats.index_new', compact('chatUsers'));
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        $chatUsers = $this->getChatUsers($currentUser);
        
        $messages = Chat::where(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        // Mark messages as read and broadcast event
        $unreadMessages = Chat::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $msg) {
            $msg->update(['is_read' => true]);
            broadcast(new \App\Events\MessageRead($msg))->toOthers();
        }

        if (request()->ajax()) {
            return response()->json([
                'user' => $user,
                'messages' => $messages
            ]);
        }

        return view('chats.show_new', compact('user', 'messages', 'chatUsers'));
    }

    private function getChatUsers($user)
    {
        // Define relevant roles to fetch automatically based on current user's role
        $autoRoles = [];
        if ($user->role === 'user') {
            $autoRoles = ['umkm'];
        } elseif ($user->role === 'umkm') {
            $autoRoles = ['user'];
        } elseif (in_array($user->role, ['admin_utama', 'admin_wilayah'])) {
            $autoRoles = ['umkm', 'user', 'admin_wilayah', 'admin_utama'];
        }

        // Fetch users who:
        // 1. Have already chatted with the current user OR
        // 2. Are in the automatically discovery roles
        return User::where('id', '!=', $user->id)
            ->where(function($query) use ($user, $autoRoles) {
                $query->whereHas('chatsSent', function($q) use ($user) {
                    $q->where('receiver_id', $user->id);
                })->orWhereHas('chatsReceived', function($q) use ($user) {
                    $q->where('sender_id', $user->id);
                });
                
                if (!empty($autoRoles)) {
                    $query->orWhereIn('role', $autoRoles);
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
                
                $u->display_name = ($u->role === 'umkm' && $u->usaha) ? $u->usaha->nama_usaha : $u->username;
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
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ]);

        $type = 'text';
        $attachmentPath = null;

        $fileName = $request->message ?? '';
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            if (empty($fileName)) {
                $fileName = $originalName;
            }
            $extension = $file->getClientOriginalExtension();
            $type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : 'file';
            $attachmentPath = $file->store('chat_attachments', 'public');
        }

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $fileName,
            'type' => $type,
            'attachment' => $attachmentPath,
        ]);

        // Broadcast the message for real-time delivery
        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        if ($request->ajax()) {
            return response()->json($chat);
        }

        return back()->with('success', 'Pesan terkirim.');
    }

    public function markAsRead(User $user)
    {
        $currentUser = Auth::user();
        $unreadMessages = Chat::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $msg) {
            $msg->update(['is_read' => true]);
            broadcast(new \App\Events\MessageRead($msg))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }
}
