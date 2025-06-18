<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RemoveNotification extends Notification implements ShouldQueue
{
    use Queueable;
   protected $group;
    /**
     * Create a new notification instance.
     */
    public function __construct($group)
    {
        $this->group=$group;
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
            'id'=>$this->id,
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'group_creator' => $this->group->creator->name,
              'type' => static::class,
        ];
    }

 public function toBroadcast($notifiable)
    {
         
        return new BroadcastMessage([
         'id'=>$this->id,
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'group_creator' => $this->group->creator->name,
        'type' => static::class,
        ]);
    }

    public function broadcastType()
    {
        return 'remove.notification';
    }
}