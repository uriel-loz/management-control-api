<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Module;

class ModulePolicy
{
    /**
     * Determine if the user can view any modules.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'modules.index')
            ->exists();
    }

    /**
     * Determine if the user can view the module.
     */
    public function view(User $user, Module $module): bool
    {
        return $user->role->permissions()
            ->where('slug', 'modules.show')
            ->exists();
    }

    /**
     * Determine if the user can create modules.
     */
    public function create(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'modules.create')
            ->exists();
    }

    /**
     * Determine if the user can update the module.
     */
    public function update(User $user, Module $module): bool
    {
        return $user->role->permissions()
            ->where('slug', 'modules.update')
            ->exists();
    }

    /**
     * Determine if the user can delete the module.
     */
    public function delete(User $user, Module $module): bool
    {
        return $user->role->permissions()
            ->where('slug', 'modules.delete')
            ->exists();
    }
}
