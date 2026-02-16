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
                'order' => 1,
                'section_id' => $home_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Users',
                'slug'  => 'users',
                'order' => 1,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Roles',
                'slug'  => 'roles',
                'order' => 2,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Notifications',
                'slug'  => 'notifications',
                'order' => 3,
                'section_id' => $administration_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Products',
                'slug'  => 'products',
                'order' => 1,
                'section_id' => $operations_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Orders',
                'slug'  => 'orders',
                'order' => 2,
                'section_id' => $operations_section->id,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Sales',
                'slug'  => 'sales',
                'order' => 3,
                'section_id' => $operations_section->id,
            ],
        ];

        Module::insert($modules);
    }
}
