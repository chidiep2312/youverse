<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class BlockUserMail extends Mailable implements ShouldQueue
{
     use Queueable, SerializesModels;
   public $user;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user=$user;
    }
    public function build()
    {
        return $this->subject('YouVerse- Khóa tài khoản')
                    ->view('admin.mail.block') 
                    ->with(['user' => $this->user]);
    }
    /**
     * Get the message envelope.
     */

}