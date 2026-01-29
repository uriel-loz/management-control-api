<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name'  => 'users.create',
            ],
            [
                'name'  => 'users.read',
            ],
            [
                'name'  => 'users.update',
            ],
            [
                'name'  => 'users.delete',
            ],
            [
                'name'  => 'roles.create',
            ],
            [
                'name'  => 'roles.read',
            ],
            [
                'name'  => 'roles.update',
            ],
            [
                'name'  => 'roles.delete',
            ],
            [
                'name'  => 'roles.mark_all',
            ],
            [
                'name'  => 'roles.unmark_all',
            ],
            [
                'name'  => 'notifications.read',
            ],
            [
                'name'  => 'notifications.mark_seen',
            ],
            [
                'name'  => 'notifications.delete',
            ],
            [
                'name'  => 'products.create',
            ],
            [
                'name'  => 'products.read',
            ],
            [
                'name'  => 'products.update',
            ],
            [
                'name'  => 'products.delete',
            ],
            [
                'name'  => 'orders.create',
            ],
            [
                'name'  => 'orders.read',
            ],
            [
                'name'  => 'orders.update',
            ],
            [
                'name'  => 'orders.delete',
            ],
            [
                'name'  => 'sales.create',
            ],
            [
                'name'  => 'sales.read',
            ],
            [
                'name'  => 'sales.update',
            ],
            [
                'name'  => 'sales.delete',
            ],
        ];

        foreach ($permissions as $key => $value) {
            list($module, $action) = explode('.', $value['name']);

            $module_record = Module::where('slug', $module)->first();

            Permission::create([
                'name'      => $value['name'],
                'module_id' => $module_record->id,
            ]);
        }
    }
}
