<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'path' => fake()->regexify('[A-Za-z0-9]{255}'),
            'product_id' => Product::factory(),
        ];
    }
}
