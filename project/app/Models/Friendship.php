<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Mail\Send2FACodeMail;
use Illuminate\Support\Facades\Auth;

class Friendship extends Model
{
    // use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'status'];

    /**
     * Người dùng gửi yêu cầu kết bạn.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Người bạn nhận yêu cầu kết bạn.
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
   
}