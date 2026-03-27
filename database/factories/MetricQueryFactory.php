<?php

namespace Database\Factories;

use App\Enums\DisplayType;
use App\Enums\MetricQuerySource;
use App\Models\MetricQuery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetricQueryFactory extends Factory
{
    protected $model = MetricQuery::class;

    public function definition(): array
    {
        return [
            'token' => fake()->uuid(),
            'user_id' => User::factory(),
            'prompt' => fake()->paragraph(),
            'generated_sql' => 'SELECT * FROM orders WHERE created_at > NOW()',
            'display_type' => fake()->randomElement(DisplayType::cases()),
            'display_config' => null,
            'source' => fake()->randomElement(MetricQuerySource::cases()),
            'template_id' => null,
            'is_saved' => fake()->boolean(),
            'is_pinned' => fake()->boolean(),
        ];
    }

    public function saved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_saved' => true,
        ]);
    }

    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }

    public function fromTemplate(string $templateId): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => MetricQuerySource::TEMPLATE,
            'template_id' => $templateId,
        ]);
    }
}
