<?php

namespace App\Services;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

class ModuleService
{
    public function showModulesByRole(): Collection
    {
        $user = auth()->user();
        $allow_modules_id = $user->role->permissions->pluck('module_id')->unique();

        $modules = Section::with([
            'modules' => function ($query) use ($allow_modules_id) {
                $query->whereIn('id', $allow_modules_id);
            }
        ])
        ->whereHas('modules', function ($query) use ($allow_modules_id) {
            $query->whereIn('id', $allow_modules_id);
        })
        ->orderBy('order', 'ASC')
        ->get();

        return $modules;
    }
}