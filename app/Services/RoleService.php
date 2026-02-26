<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService {
    public function showAll() : Collection
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
    
    public function createOrUpdateRole(array $data) : Role
    {
        $role = null;

        if (isset($data['id'])) {
            $role = Role::find($data['id']);
            $role->update(['name' => $data['role']]);
        } else {
            $role = Role::create(['name' => $data['role']]);
        }

        $modules = $data['modules'] ?? [];

        $role->permissions()->sync($modules);

        return $role;
    }
    
    public function deleteRole(string $id) : void
    {
        $role = Role::find($id);
        $role->permissions()->detach();
        $role->delete();
    }
}
