<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationVector extends Model
{
     protected $fillable = ['keyword', 'embedding'];

    protected $casts = [
        'embedding' => 'array', 
    ];
}