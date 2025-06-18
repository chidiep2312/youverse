<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment;
    public  $threadId;

    /**
     * Create a new event instance.
     */
    public function __construct($comment, $threadId)
    {
        $this->comment = $comment;
        $this->threadId= $threadId;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'commenter' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id,
                'avatar' => $this->comment->user->avatar,
            ],

            'created_at' => $this->comment->created_at->toDateTimeString(),
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
      
            return [
                new Channel('Thread'.$this->threadId),
            ];
      
    }
     public function broadcastAs()
    {
        return 'comment.post';
    }
}