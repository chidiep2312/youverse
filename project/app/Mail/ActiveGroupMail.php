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

class ActiveGroupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $group;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Group $group)
    {
        $this->group = $group;
        $this->user = $user;
    }
    public function build()
    {
        return $this->subject('YouVerse - Thông báo nhóm được kích hoạt trở lại')
            ->view('admin.mail.active-group')
            ->with(['group' => $this->group, 'user' => $this->user]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo nhóm được kích hoạt trở lại',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.active-group',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}