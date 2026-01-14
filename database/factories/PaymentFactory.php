<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => fake()->regexify('[A-Za-z0-9]{45}'),
            'method' => fake()->regexify('[A-Za-z0-9]{45}'),
            'quantity' => fake()->randomFloat(2, 0, 99999999999999.99),
            'order_id' => Order::factory(),
        ];
    }
}
