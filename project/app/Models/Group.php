<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['user_id','is_active', 'name','member_count', 'description','bgr'];
  public function users()
{
    return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')
        ->withPivot('status')
        ->withTimestamps();
}
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function isAdmin(User $user){
        return $this->user_id===$user->id;
    }

    
public function joinRequests()
{
    return $this->belongsToMany(User::class, 'group_users')
                ->withPivot('status')
                ->wherePivot('status', 'pending');
}
    public function members()
{
    return $this->belongsToMany(User::class, 'group_users')
                ->withPivot('status')
                ->wherePivot('status', 'accepted');
}
}