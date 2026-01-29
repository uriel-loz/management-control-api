<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(
            [
                'name' => 'Admin',
            ]
        );

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $role->permissions()->attach($permission->id);
        }
    }
}
