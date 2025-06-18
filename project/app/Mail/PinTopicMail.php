<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PinTopicMail extends Mailable
{
    use Queueable, SerializesModels;
protected $name;
protected $title;
    /**
     * Create a new message instance.
     */
    public function __construct($name,$title)
    {
        $this->name=$name;
        $this->title=$title;
    }

    /**
     * Get the message envelope.
     */
     public function build()
    {
        return $this->subject('YouVerse - Thông báo ghim bài viết')
            ->view('admin.mail.pin')
            ->with(['name' => $this->name, 'title' => $this->title]);
    }
}