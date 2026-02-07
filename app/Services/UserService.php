<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService {
    public function showAll() : LengthAwarePaginator
    {
        return User::with('role:id,name')
            ->select('id', 'name', 'email', 'phone', 'is_customer', 
                'role_id', 'created_at', 'updated_at')
            ->paginate(request()->per_page ?? 10);
    }

    public function createOrUpdateUser(array $data) : void
    {
        if (isset($data['password'])) 
            $data['password'] = bcrypt($data['password']);

        User::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function deleteUser(string $id) : void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}