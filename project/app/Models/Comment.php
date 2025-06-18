<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //use HasFactory;

    protected $fillable = ['post_id','thread_id', 'user_id', 'content'];

    /**
     * Bài viết mà bình luận thuộc về.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id','id');
    }
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id','id');
    }

    /**
     * Người dùng đã tạo bình luận.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}