<?php

namespace App\Services;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleService
{
    public function showAll(): Collection
    {
        return Cache::remember('modules.all', 3600, function() {
            return Section::with([
                'modules' => function ($query) {
                    $query->orderBy('order', 'ASC');
                },
                'modules.permissions'
            ])
            ->orderBy('order', 'ASC')
            ->get();
        });
    }
    
    public function showModulesByRole(): Collection
    {
        $userId = auth()->id();

        return Cache::remember("modules.by_role.{$userId}", 3600, function() {
            $user = User::with('role.permissions')->find(auth()->id());
            $allow_modules_id = $user->role->permissions->pluck('module_id')->unique();

            return Section::with([
                'modules' => function ($query) use ($allow_modules_id) {
                    $query->whereIn('id', $allow_modules_id)
                        ->orderBy('order', 'ASC');
                }
            ])
            ->whereHas('modules', function ($query) use ($allow_modules_id) {
                $query->whereIn('id', $allow_modules_id);
            })
            ->orderBy('order', 'ASC')
            ->get();
        });
    }

    public function invalidateCache(): void
    {
        Cache::forget('modules.all');
        // Invalidar todos los cachés de módulos por rol
        Cache::flush();
    }
}