<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // use HasFactory;

    protected $fillable = ['post_id','url'];

    public function post()
    {
        return $this->belongsTo(Post::class,'post_id','id');
    }

   
}
