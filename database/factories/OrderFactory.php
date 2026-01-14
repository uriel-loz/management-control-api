<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => fake()->regexify('[A-Za-z0-9]{45}'),
            'total_products' => fake()->numberBetween(-10000, 10000),
            'total_price' => fake()->randomFloat(2, 0, 99999999999999.99),
            'user_id' => User::factory(),
        ];
    }
}
