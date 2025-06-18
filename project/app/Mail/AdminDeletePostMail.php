<?php

namespace App\Mail;
use App\Models\User;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminDeletePostMail extends Mailable implements ShouldQueue
{
     use Queueable, SerializesModels;
   public $post;
   public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($post, $user)
    {
        $this->post=$post;
         $this->user=$user;
    }
    public function build()
    {
        return $this->subject('YouVerse - Bài viết vi phạm')
                    ->view('admin.mail.delete-post-admin') 
                    ->with(['post' => $this->post, 'user'=>$this->user]);
    }
    /**
     * Get the message envelope.
     */
  
}