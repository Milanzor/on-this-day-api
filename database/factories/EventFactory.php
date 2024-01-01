<?php

namespace Database\Factories;

use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text,
            'month' => $this->faker->numberBetween(1, 12),
            'day' => $this->faker->numberBetween(1, 31),
            'category' => $this->faker->randomElement(Category::cases()),
            'language' => $this->faker->randomElement(Language::cases()),
            'year' => $this->faker->boolean() ? $this->faker->numberBetween(1, 2021) : null,
            'hash' => hash('sha256', $this->faker->text),
            'url' => $this->faker->boolean() ? $this->faker->url : null,
            'source' => Source::Seed,
            'happiness' => $this->faker->numberBetween(-100, 100),
        ];
    }
}
