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
                'name'  =>  'Lectura',
                'slug'  => 'home.read',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'users.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'users.read',
            ],
            [
                'name'  =>  'Actualización',
                'slug'  => 'users.update',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'users.delete',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'roles.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'roles.read',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'categories.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'categories.read',
            ],
            [
                'name'  =>  'Actualización',
                'slug'  => 'categories.update',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'categories.delete',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'products.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'products.read',
            ],
            [
                'name'  =>  'Actualización',
                'slug'  => 'products.update',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'products.delete',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'orders.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'orders.read',
            ],
            [
                'name'  =>  'Actualización',
                'slug'  => 'orders.update',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'orders.delete',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'sales.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'sales.read',
            ],
            [
                'name'  =>  'Actualización',
                'slug'  => 'sales.update',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'sales.delete',
            ],
            [
                'name'  =>  'Creación',
                'slug'  => 'metrics.create',
            ],
            [
                'name'  =>  'Lectura',
                'slug'  => 'metrics.read',
            ],
            [
                'name'  =>  'Eliminación',
                'slug'  => 'metrics.delete',
            ],
        ];

        foreach ($permissions as $key => $value) {
            list($module, $action) = explode('.', $value['slug']);

            $module_record = Module::where('slug', $module)->first();

            Permission::create([
                'name'      => $value['name'],
                'slug'      => $value['slug'],
                'module_id' => $module_record->id,
            ]);
        }
    }
}
