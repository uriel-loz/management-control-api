<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home_section = Section::where('slug', 'home')->first();
        $administration_section = Section::where('slug', 'administration')->first();
        $operations_section = Section::where('slug', 'operations')->first();

        $modules = [
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Home',
                'slug'  => 'home',
                'icon'  => 'home',
                'order' => 1,
                'section_id' => $home_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Usuarios',
                'slug'  => 'users',
                'icon'  => 'groups',
                'order' => 1,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Roles',
                'slug'  => 'roles',
                'icon'  => 'security',
                'order' => 2,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Notificaciones',
                'slug'  => 'notifications',
                'icon'  => 'notifications',
                'order' => 3,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Productos',
                'slug'  => 'products',
                'icon'  => 'inventory_2',
                'order' => 1,
                'section_id' => $operations_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Ordenes',
                'slug'  => 'orders',
                'icon'  => 'orders',
                'order' => 2,
                'section_id' => $operations_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Ventas',
                'slug'  => 'sales',
                'icon'  => 'request_quote',
                'order' => 3,
                'section_id' => $operations_section->id,
            ],
        ];

        Module::insert($modules);
    }
}
