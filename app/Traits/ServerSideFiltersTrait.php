<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ServerSideFiltersTrait
{
    protected function applyServerSideFilters(
        Builder $query,
        array $filters,
        array $columnMap = [],
        array $customFilters = []
    ): Builder {
        foreach ($filters as $key => $value) {
            // Caso 1: Filtro custom (tiene callback)                                                              
            if (isset($customFilters[$key]) && is_callable($customFilters[$key])) {
                $customFilters[$key]($query, $value);
                continue;
            }

            // Caso 2: Filtro mapeado (usa el mapeo)                                                               
            if (isset($columnMap[$key])) {
                $column = $columnMap[$key];
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