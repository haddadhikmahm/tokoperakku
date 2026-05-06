<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    public function update(User $user, Chat $chat): bool
    {
        return $user->id === $chat->sender_id;
    }

    public function delete(User $user, Chat $chat): bool
    {
        return $user->id === $chat->sender_id;
    }
}
