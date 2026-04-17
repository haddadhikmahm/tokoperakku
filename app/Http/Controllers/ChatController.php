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
        
        // Get unique users with whom the current user has chatted
        $chatUsers = User::whereHas('chatsSent', function($q) use ($user) {
            $q->where('receiver_id', $user->id);
        })->orWhereHas('chatsReceived', function($q) use ($user) {
            $q->where('sender_id', $user->id);
        })->get();

        return view('chats.index', compact('chatUsers'));
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        $messages = Chat::where(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($currentUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        Chat::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chats.show', compact('user', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }
}
