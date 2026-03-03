<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ServerSideFiltersTrait
{
    protected function applyServerSideFilters(
        Builder $query,
        array $filters,
        array $column_map = [],
        array $custom_filters = []
    ): Builder {
        foreach ($filters as $key => $value) {
            // Caso 1: Filtro custom (tiene callback)                                                              
            if (isset($custom_filters[$key]) && is_callable($custom_filters[$key])) {
                $custom_filters[$key]($query, $value);
                continue;
            }

            // Caso 2: Filtro mapeado (usa el mapeo)                                                               
            if (isset($column_map[$key])) {
                $column = $column_map[$key];
                $query->where($column, 'like', "%$value%");
                continue;
            }

            // Caso 3: Filtro automático (usa el key directamente)                                                 
            // Solo si contiene punto (tabla.columna) o existe en la tabla                                         
            if (str_contains($key, '.')) {
                $query->where($key, 'like', "%$value%");
            }
        }

        return $query;
    }
}