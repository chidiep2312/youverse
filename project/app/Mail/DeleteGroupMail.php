<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeleteGroupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $group;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $group)
    {
        $this->group = $group;
        $this->user = $user;
    }
    public function build()
    {
        return $this->subject('YouVerse- Thông báo nhóm bị ngừng hoạt động')
            ->view('admin.mail.delete-group')
            ->with(['group' => $this->group, 'user' => $this->user]);
    }
    /**
     * Get the message envelope.
     */
  
}