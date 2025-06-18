<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ApproveViolationPostMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $title;
    public $time;
    public $des;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $title, $time, $des)
    {
        $this->user = $user;
        $this->title = $title;
        $this->time = $time;
        $this->des = $des;
    }
    public function build()
    {
        return $this->subject('YouVerse - Thông báo phê duyệt bài viết')
            ->view('admin.mail.approve-violation')
            ->with([
                'user' => $this->user,
                'title' => $this->title,
                'time' => $this->time,
                'des' => $this->des
            ]);
    }
}