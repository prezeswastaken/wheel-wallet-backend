<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'car_id' => \App\Models\Car::factory(),
            'message' => Str::random(40),
            'username' => $this->faker->randomElement(['Cezary', 'Ozzy', 'prezeswastaken', 'Juan']),
        ];
    }
}
