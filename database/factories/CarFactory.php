<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model' => $this->faker->randomElement(['Opel', 'Nissan', 'Toyota', 'Smart']),
            'owner_id' => \App\Models\User::factory(),
            'coowner_id' => null,
            'status' => $this->faker->randomElement(['Factory New', 'Battle-scarred', 'Crashed', 'Minimal wear']),
            'code' => Str::random(14),
        ];
    }
}
