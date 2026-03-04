<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    /**
     * Determine if the user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.index')
            ->exists();
    }

    /**
     * Determine if the user can view the role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.show')
            ->exists();
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.create')
            ->exists();
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.update')
            ->exists();
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.delete')
            ->exists();
    }

    /**
     * Determine if the user can restore the role.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.restore')
            ->exists();
    }

    /**
     * Determine if the user can permanently delete the role.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->role->permissions()
            ->where('slug', 'roles.force-delete')
            ->exists();
    }
}
