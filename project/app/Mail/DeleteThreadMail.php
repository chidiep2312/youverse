<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Thread;
use App\Models\User;

class DeleteThreadMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $thread_id;
    public $thread_content;
    public $user_name;
    /**
     * Create a new message instance.
     */
    public function __construct($thread_id, $thread_content,$user_name)
    {
        $this->thread_id = $thread_id;
        $this->thread_content = $thread_content;
        $this->user_name = $user_name;
    }
    public function build()
    {
        return $this->subject('YouVerse - Bài viết ngắn vi phạm')
            ->view('admin.mail.delete-thread')
            ->with(['thread_id' => $this->thread_id, 'thread_content' => $this->thread_content, 'user_name' => $this->user_name]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bài viết ngắn vi phạm',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.delete-thread',
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