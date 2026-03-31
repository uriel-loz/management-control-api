<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MetricQuery;

class MetricQueryPolicy
{
    /**
     * Determine if the user can create metric queries.
     */
    public function create(User $user): bool
    {
        return $user->role->permissions()
            ->where('slug', 'metrics.create')
            ->exists();
    }
}
