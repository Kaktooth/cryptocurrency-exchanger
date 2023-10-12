<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rates>
 */
class RatesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'short_name' => fake()->currencyCode(),
            'value' => fake()->unique()->randomFloat(),
        ];
    }
}
