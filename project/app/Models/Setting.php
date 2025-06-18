<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
     protected $fillable = ['banner', 'user_id','support_email','facebook','hotline','des'];
       protected $casts = [
        'banner' => 'array',
    ];
}