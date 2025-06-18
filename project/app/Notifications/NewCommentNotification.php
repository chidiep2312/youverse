<?php

namespace App\Notifications;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $comment;
    protected $author;

    /**
     * Create a new notification instance.
     */
    public function __construct($comment, $author)
    {
        //
        $this->comment = $comment;
        $this->author = $author;
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
         if ($this->comment->post_id != null) {
        return [
            'id' => $this->id,
            'comment_type'=>'Post',
            'content' => $this->comment->content,
            'post'=>Str::limit(strip_tags($this->comment->post->title), 20),
                 'post_id'=>$this->comment->post->id,
            'commenter' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id,
                'avatar' => $this->comment->user->avatar,
            ],
            'author' => [
                'id' => $this->author->id
            ],
            'created_at' => $this->comment->created_at->toDateTimeString(),
             'type' => static::class,
        ];
        }else{
             return [
            'id' => $this->id,
             'comment_type'=>'Thread',
            'content' => $this->comment->content,
            'thread'=>Str::limit(strip_tags($this->comment->thread->content), 20),
             'thread_id'=>$this->comment->thread->id,
            'commenter' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id,
                'avatar' => $this->comment->user->avatar,
            ],
            'author' => [
                'id' => $this->author->id
            ],
            'created_at' => $this->comment->created_at->toDateTimeString(),
             'type' => static::class,
        ];
        }
      
    }

    public function toBroadcast($notifiable)
    {  if ($this->comment->post_id != null) {
        return  new BroadcastMessage([
            'id' => $this->id,
             'comment_type'=>'Post',
            'content' => $this->comment->content,
            'post'=>$this->comment->post->title,
             'post_id'=>$this->comment->post_id,
            'commenter' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id,
                'avatar' => $this->comment->user->avatar,
            ],
            'author' => [
                'id' => $this->author->id
            ],
            'created_at' => $this->comment->created_at->toDateTimeString(),
             'type' => static::class,
        ]);
        }else{
             return  new BroadcastMessage( [
            'id' => $this->id,
            'content' => $this->comment->content,
            'thread'=>$this->comment->thread->content,
             'thread_id'=>$this->comment->thread->id,
                    'comment_type'=>'Thread',
            'commenter' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id,
                'avatar' => $this->comment->user->avatar,
            ],
            'author' => [
                'id' => $this->author->id
            ],
            'created_at' => $this->comment->created_at->toDateTimeString(),
             'type' => static::class,
        ]);
        }
    }

    public function broadcastType()
    {
        return 'comment.notification';
    }
}