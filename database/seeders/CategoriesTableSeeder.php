<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now()->toDateTimeString();

        $categories = [
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Electrónica',
                'slug'        => 'electronica',
                'description' => 'Dispositivos, gadgets y accesorios electrónicos',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Ropa y Moda',
                'slug'        => 'ropa-y-moda',
                'description' => 'Prendas de vestir para hombre, mujer y niños',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Hogar y Decoración',
                'slug'        => 'hogar-y-decoracion',
                'description' => 'Muebles, decoración y artículos para el hogar',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Deportes y Fitness',
                'slug'        => 'deportes-y-fitness',
                'description' => 'Equipamiento deportivo y artículos para ejercicio',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Belleza y Cuidado Personal',
                'slug'        => 'belleza-y-cuidado-personal',
                'description' => 'Cosméticos, perfumes y productos de higiene',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Alimentos y Bebidas',
                'slug'        => 'alimentos-y-bebidas',
                'description' => 'Productos alimenticios, snacks y bebidas',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Juguetes y Juegos',
                'slug'        => 'juguetes-y-juegos',
                'description' => 'Juguetes educativos, juegos de mesa y entretenimiento',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Libros y Educación',
                'slug'        => 'libros-y-educacion',
                'description' => 'Libros, cursos y material educativo',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        Category::insert($categories);
    }
}
