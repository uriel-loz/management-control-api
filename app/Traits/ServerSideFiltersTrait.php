<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ServerSideFiltersTrait
{
    protected function applyServerSideFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $column => $value) {
            if (!$value || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $column)) continue;
            $query->where($column, 'LIKE', "%$value%");
        }
        return $query;
    }

    protected function applyServerSideSort(
        Builder $query,
        string $default_column = 'updated_at',
        string $default_direction = 'desc'
    ): Builder {
        $orderBy   = request()->input('orderBy', $default_column);
        $direction = strtolower(request()->input('order', $default_direction));
        $order     = in_array($direction, ['asc', 'desc']) ? $direction : $default_direction;

        $column = preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $orderBy) ? $orderBy : $default_column;
        $query->orderBy($column, $order);

        return $query;
    }
}
