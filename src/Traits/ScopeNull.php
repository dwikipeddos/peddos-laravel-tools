<?php

namespace Dwikipeddos\PeddosLaravelTools\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopeNull
{
    public function scopeNull(Builder $builder, $columns): Builder
    {
        $columns = explode('|', $columns);
        foreach ($columns as $column) {
            $builder->whereNull($column);
        }
        return $builder;
    }
}
