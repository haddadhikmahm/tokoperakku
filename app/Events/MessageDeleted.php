<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_id;
    public $receiver_id;
    public $sender_id;
    public $delete_type; // 'everyone' or 'me'

    public function __construct($chat_id, $sender_id, $receiver_id, $delete_type = 'everyone')
    {
        $this->chat_id = $chat_id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->delete_type = $delete_type;
    }

    public function broadcastOn(): array
    {
        // For 'everyone', notify both. For 'me', only notify the sender (though usually client handles it).
        if ($this->delete_type === 'everyone') {
            return [
                new PrivateChannel('chat.' . $this->receiver_id),
                new PrivateChannel('chat.' . $this->sender_id),
            ];
        }
        
        return [
            new PrivateChannel('chat.' . $this->sender_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.deleted';
    }
}
