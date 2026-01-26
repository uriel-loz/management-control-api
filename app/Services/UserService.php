<?php

namespace App\Services;

use App\Models\User;

Class UserService {
    public function showAll() : array
    {
        $users = User::with('role:id,name')
            ->select('id', 'name', 'email', 'phone', 'is_customer', 
                'role_id', 'created_at', 'updated_at')
            ->paginate(request()->per_page ?? 10);

        return $users->toArray();
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