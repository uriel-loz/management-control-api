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
        $this->call(SectionsTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(SetPermissionsSeeder::class);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@manager.com',
            'phone' => '5555555555',
            'password'  => bcrypt('12341234'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
    }
}
