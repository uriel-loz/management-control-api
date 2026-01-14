<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => fake()->dateTime(),
            'password' => fake()->password(),
            'remember_token' => fake()->uuid(),
            'is_customer' => fake()->boolean(),
            'role_id' => Role::factory(),
        ];
    }
}
