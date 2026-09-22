<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movement>
 */
class MovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'value' => fake()->randomFloat(2, 10, 1000),
            'date_buy' => now()->format('d/m/Y'),
            'period' => now()->format('m/Y'),
            'description' => fake()->sentence(),
            'installment' => '1/1',
            'group_id' => null,
        ];
    }
}
