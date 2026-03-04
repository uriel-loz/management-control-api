<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class RoleService {
    public function showAll(): Collection
    {
        $roles = Role::with(
            [
                'permissions:id,name,module_id',
                'permissions.module:id,name',
            ]
        )
        ->withCount('users as quantity_users')
        ->get();

        return $roles;
    }

    public function createOrUpdateRole(array $data): Role
    {
        $role = null;

        if (isset($data['id'])) {
            $role = Role::findOrFail($data['id']);
            $modules = $data['modules'] ?? [];
            $role->permissions()->sync($modules);
        } else {
            $role = Role::create(['name' => $data['role']]);
        }

        // Invalidar cache de módulos
        $this->invalidateCache();

        return $role;
    }

    public function deleteRole(string $id): void
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();

        // Invalidar cache de módulos
        $this->invalidateCache();
    }

    public function invalidateCache(): void
    {
        Cache::forget('modules.all');
        // Invalidar todos los cachés de módulos por rol
        Cache::flush();
    }
}
