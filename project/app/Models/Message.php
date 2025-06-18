<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //   use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'content'];

    /**
     * Người gửi tin nhắn.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id','id');
    }

    /**
     * Người nhận tin nhắn.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id','id');
    }
}
