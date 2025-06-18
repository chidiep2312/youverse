<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
     protected $fillable = ['post_id', 'user_id', 'status','reason','details'];
       public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function post()
{
    return $this->belongsTo(Post::class, 'post_id', 'id');
}

}