<?php

namespace App\Notifications;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class leaveNotification extends Notification
{
    use Queueable;
 protected $group;
 protected $user;
    /**
     * Create a new notification instance.
     */
     public function __construct($group,$user)
    {
        $this->group=$group;
          $this->user=$user;
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

 public function toDatabase($notifiable)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user->id,
             'user_name'=>$this->user->name,
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
           'user_id'=>$this->user->id,
             'user_name'=>$this->user->name,
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'group_creator' => $this->group->creator->name,
        'type' => static::class,
        ]);
    }

    public function broadcastType()
    {
        return 'leave.notification';
    }
}