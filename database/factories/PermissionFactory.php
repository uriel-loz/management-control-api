<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ParentPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'requires_authorization' => fake()->randomDigitNotNull(),
            'parent_permission_id' => ParentPermission::factory(),
            'module_id' => Module::factory(),
        ];
    }
}
