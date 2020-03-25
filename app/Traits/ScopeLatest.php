<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait ScopeLatest
{

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}
