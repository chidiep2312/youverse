<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bgr',
        'slogan'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withPivot('status')
            ->withTimestamps();
    }
    public function isFriend($id)
    {
        return Friendship::where(function ($query) use ($id) {
            $query->where('user_id', $this->id)
                ->where('friend_id', $id)->where('status', 'accepted');
        })->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)
                ->where('friend_id', $this->id)->where('status', 'accepted');
        })->exists();
    }

    public function posts()
    {
        return  $this->hasMany(Post::class, 'user_id', 'id');
    }
    public function threads()
    {
        return  $this->hasMany(Thread::class, 'user_id', 'id');
    }
    public function folders()
    {
        return  $this->hasMany(Folder::class, 'user_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users', 'user_id', 'group_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
    public function twoFactorCodes()
    {
        return $this->hasMany(TwoFactorCode::class);
    }
  
    public function blocks()
    {
        return $this->hasMany(Block::class, 'user_id');
    }
public function isBlockedBy($userId)
{
    return Block::where('user_id', $userId)
                ->where('blocked_user_id', Auth::id())
                ->exists();
}
public function blockUser($userId)
{
    return Block::where('blocked_user_id', $userId)
                ->where('user_id', Auth::id())
                ->exists();
}
 public function blockedBy()
    {
        return $this->hasMany(Block::class, 'blocked_user_id');
    }

}