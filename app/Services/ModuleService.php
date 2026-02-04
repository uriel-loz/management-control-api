<?php

namespace App\Services;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

Class ModuleService {
    public function showAll() : Collection
    {
        $modules = Section::with([
            'modules' => function ($query) {
                $query->orderBy('order', 'ASC');
            }
        ])
        ->orderBy('order', 'ASC')
        ->get();

        return $modules;
    }
}