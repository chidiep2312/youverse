<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; 
    }

    public function toDatabase($notifiable)
    {
        return [
             'id'=>$this->id,
            'sender_id' =>$this->message->sender->id,
            'sender_name' => $this->message->sender->name,
             'sender_avatar' =>$this->message->sender->avatar,
            'content' => $this->message->content,
              'type' => static::class,
        ];
    }

    public function toBroadcast($notifiable)
    {
         
        return new BroadcastMessage([
             'id'=>$this->id,
           'sender_id' =>$this->message->sender->id,
            'sender_name' => $this->message->sender->name,
             'sender_avatar' =>$this->message->sender->avatar,
            'content' => $this->message->content,
              'type' => static::class,
        ]);
    }

    public function broadcastType()
    {
        return 'message.notification';
    }
}