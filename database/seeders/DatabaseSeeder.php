<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $role = Role::create(
            [
                'name' => 'Admin',
            ]
        );

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@manager.com',
            'phone' => '5555555555',
            'password'  => bcrypt('12341234'),
            'role_id' => $role->id,
        ]);
    }
}
