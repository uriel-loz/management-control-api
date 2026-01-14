<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'price' => fake()->randomFloat(2, 0, 99999999999999.99),
            'quantity' => fake()->numberBetween(-10000, 10000),
            'description' => fake()->text(),
        ];
    }
}
