<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'success' => fake()->boolean(),
            'timestamp' => fake()->randomNumber(),
            'base' => fake()->unique()->currencyCode(),
            'date' => fake()->date(),
        ];
    }
}