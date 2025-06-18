<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    //
    protected $fillable = ['user_id', 'name', 'bgr','des', 'tag_id'];
      public function posts()
    {
        return $this->hasMany(Post::class, 'folder_id', 'id');
    }
      public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }
      public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}