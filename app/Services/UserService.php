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

        $filters = request()->input('filters', []);

        $column_map = [
            'name' => 'users.name',
            'email' => 'users.email',
            'phone' => 'users.phone',
            'role' => 'roles.name',
        ];

        $custom_filters = [
            'type' => function ($query, $value) {
                $isCustomer = strtolower($value) === 'customer' ? 1 : 0;
                $query->where('users.is_customer', $isCustomer);
            },
            'created_at' => function ($query, $value) {
                $query->whereRaw("DATE_FORMAT(users.created_at, '%d/%m/%Y') LIKE ?", ["%$value%"]);
            },
            'updated_at' => function ($query, $value) {
                $query->whereRaw("DATE_FORMAT(users.updated_at, '%d/%m/%Y') LIKE ?", ["%$value%"]);
            },
        ];

        $this->applyServerSideFilters($query, $filters, $column_map, $custom_filters);

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