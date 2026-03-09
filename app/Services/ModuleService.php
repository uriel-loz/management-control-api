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
            $allowed_permissions = $user->role->permissions;
            $allow_modules_id = $user->role->permissions->pluck('module_id')->unique();

            return Section::with([
                'modules' => function ($query) use ($allow_modules_id) {
                    $query->whereIn('id', $allow_modules_id)
                        ->orderBy('order', 'ASC');
                },
                'modules.permissions' => function ($query) use ($allowed_permissions) {
                    $query->whereIn('id', $allowed_permissions->pluck('id'));
                }
            ])
            ->whereHas('modules', function ($query) use ($allow_modules_id) {
                $query->whereIn('id', $allow_modules_id);
            })
            ->orderBy('order', 'ASC')
            ->get();
        });
    }

    public function userHasAccessToModule(string $module_slug): bool
    {
        $user = User::with('role.permissions.module')->find(auth()->id());

        $has_access = $user->role->permissions
            ->filter(
                fn($permission) => $permission->module?->slug === $module_slug && 
                    str_contains($permission->slug, '.read')
            )
            ->isNotEmpty();

        if (!$has_access) 
            throw new \Exception('You do not have access to this module.', 403);

        return true;
    }

    public function invalidateCache(): void
    {
        Cache::forget('modules.all');
        Cache::flush();
    }
}