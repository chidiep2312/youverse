<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PostNotFlaggedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
     public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_flag', false)->orWhere('is_flag',null);
    }
}