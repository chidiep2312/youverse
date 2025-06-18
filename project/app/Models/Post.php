<?php

namespace App\Models;
use App\Models\Scopes\PostNotFlaggedScope;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // use HasFactory;

    protected $fillable = ['group_id','embedding','vio_embedding', 'user_id', 'folder_id','title', 'content', 'tag_id', 'image', 'status','scheduled_at', 'thumbnail','is_flag'];
  protected $casts = [
        'vio_embedding' => 'array',
      
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class, 'post_id', 'id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
     public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    public function views()
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'post_id', 'id');
    }
     protected static function booted()
    {
        static::addGlobalScope(new PostNotFlaggedScope);
    }
      public static function withFlagged()
    {
        return static::withoutGlobalScope(PostNotFlaggedScope::class);
    }
}