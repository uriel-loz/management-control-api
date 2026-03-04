<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.index')
            ->exists();
    }

    /**
     * Determine if the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.show')
            ->exists();
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.create')
            ->exists();
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.update')
            ->exists();
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.delete')
            ->exists();
    }

    /**
     * Determine if the user can restore the user.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.restore')
            ->exists();
    }

    /**
     * Determine if the user can permanently delete the user.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role->permissions()
            ->where('slug', 'users.force-delete')
            ->exists();
    }
}
