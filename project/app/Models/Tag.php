<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // use HasFactory;

    protected $fillable = ['tag_name'];

    /**
     * Các bài viết có tag này.
     */
    public function posts()
    {
        return $this->hasMany(Post::class,'tag_id','id');
    }
}
