<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Send2FACodeMail extends Mailable
{
    use Queueable, SerializesModels;
protected $code;
    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        //
        $this->code=$code;
    }

     public function build()
    {
        return $this->subject('YouVerse - Gửi mã xác thực')
                    ->view('admin.mail.2fa') 
                    ->with(['code'=>$this->code]);
    }
    /**
     * Get the message envelope.
     */
 

    /**
     * Get the message content definition.
     */
  
}