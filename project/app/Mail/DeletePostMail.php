<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;
class DeletePostMail extends Mailable implements ShouldQueue
{
     use Queueable, SerializesModels;
   public $report_reason;
   public $report_title;
   public $report_detail;
   public $user_name;
    /**
     * Create a new message instance.
     */
    public function __construct($report_title,$report_reason,$report_detail,$user_name)
    {
        $this->report_reason=$report_reason;
          $this->report_detail=$report_detail;
          $this->report_title=$report_title;
         $this->user_name=$user_name;
    }
    public function build()
    {
        return $this->subject('YouVerse - Bài viết vi phạm')
                    ->view('admin.mail.delete-post') 
                    ->with(['report_title'=>$this->report_title,'report_reason' => $this->report_reason, 'report_detail' => $this->report_detail, 
        'user_name'=>$this->user_name]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bài viết vi phạm',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.delete-post',
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