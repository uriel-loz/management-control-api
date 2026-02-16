<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Home',
                'slug'  => 'home',
                'order' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Administration',
                'slug'  => 'administration',
                'order' => 2,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name'  => 'Operations',
                'slug'  => 'operations',
                'order' => 3,
            ],
        ];

        Section::insert($sections);
    }
}
