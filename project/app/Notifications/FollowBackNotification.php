<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowBackNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $friendship;
    /**
     * Create a new notification instance.
     */
    public function __construct($friendship)
    {
        $this->friendship = $friendship;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->id,
            'friendship_id' => $this->friendship->id,
            'friendship_friend' => $this->friendship->friend->name,
            'type' => static::class,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'id' => $this->id,
            'friendship_id' => $this->friendship->id,
            'friendship_friend' => $this->friendship->friend->name,
            'type' => static::class,
        ]);
    }

    public function broadcastType()
    {
        return 'followback.notification';
    }
}
