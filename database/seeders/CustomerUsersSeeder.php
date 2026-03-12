<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class CustomerUsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin_role_id = Role::where('name', 'Admin')->first()->id;

        $customers = [
            [
                'name'        => 'Carlos Mendoza',
                'email'       => 'carlos.mendoza@email.com',
                'phone'       => '5551001001',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'María García',
                'email'       => 'maria.garcia@email.com',
                'phone'       => '5551001002',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Luis Rodríguez',
                'email'       => 'luis.rodriguez@email.com',
                'phone'       => '5551001003',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Ana Martínez',
                'email'       => 'ana.martinez@email.com',
                'phone'       => '5551001004',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Jorge López',
                'email'       => 'jorge.lopez@email.com',
                'phone'       => '5551001005',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Sofía Hernández',
                'email'       => 'sofia.hernandez@email.com',
                'phone'       => '5551001006',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Miguel Torres',
                'email'       => 'miguel.torres@email.com',
                'phone'       => '5551001007',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Laura Sánchez',
                'email'       => 'laura.sanchez@email.com',
                'phone'       => '5551001008',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Roberto Díaz',
                'email'       => 'roberto.diaz@email.com',
                'phone'       => '5551001009',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
            [
                'name'        => 'Valentina Ruiz',
                'email'       => 'valentina.ruiz@email.com',
                'phone'       => '5551001010',
                'password'    => Hash::make('password123'),
                'is_customer' => true,
                'role_id'     => $admin_role_id,
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
