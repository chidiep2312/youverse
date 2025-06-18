<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //
    
    protected $fillable = ['user_id', 'content','type','tag_id','title','is_pinned'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
      public function comments()
    {
        return $this->hasMany(Comment::class, 'thread_id', 'id');
    }
      public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

}