<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message =  $message;
    }
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'sender' => [
                'name' => $this->message->sender->name,
                'id' => $this->message->sender->id,
                'avatar' => $this->message->sender->avatar,
            ],
            'receiver' => [
                'name' => $this->message->receiver->name,
                'id' => $this->message->receiver->id,
                'avatar' => $this->message->receiver->avatar,
            ],
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $id1 = min($this->message->sender_id, $this->message->receiver_id);
        $id2 = max($this->message->sender_id, $this->message->receiver_id);


        return [
            new PrivateChannel("chat-channel.{$id1}.{$id2}")
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}