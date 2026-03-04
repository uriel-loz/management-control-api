<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ServerSideFiltersTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    use ServerSideFiltersTrait;

    public function showAll(): LengthAwarePaginator
    {
        $query = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.is_customer',
                'users.role_id',
                'roles.name as role',
                DB::raw('IF(users.is_customer, "Customer", "Admin") as type'),
                'users.created_at',
                'users.updated_at'
            );

        $this->applyServerSideFilters($query, request()->input('filters', []));
        $this->applyServerSideSort($query, 'users.updated_at', 'desc');

        return $query->paginate(request()->per_page ?? 10);
    }

    public function createOrUpdateUser(array $data): void
    {
        if (isset($data['password']))
            $data['password'] = bcrypt($data['password']);

        User::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function deleteUser(string $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}