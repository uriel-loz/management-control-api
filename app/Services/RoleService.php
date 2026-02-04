<?php

namespace App\Services;

use App\Models\Role;

Class RoleService {
    public function showAll() : array
    {
        $roles = Role::with(
            [
                'permissions:id,name,module_id',
                'permissions.module:id,name'
            ]
        )->get()
        ->toArray();

        return $roles;
    }
    
    public function createOrUpdateRole(array $data) : void
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
    }
    
    public function deleteRole(string $id) : void
    {
        $role = Role::find($id);
        $role->permissions()->detach();
        $role->delete();
    }
}
