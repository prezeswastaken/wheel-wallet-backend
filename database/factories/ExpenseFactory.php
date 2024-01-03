<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
            'name' => $this->faker->randomElement(['Tankowanie', 'Wymiana opon', 'Wymiana szyby', 'Naprawa uszkodzonej uszczelki']),
            'cost' => $this->faker->randomNumber(5),
            'date' => $this->faker->date(),
            'planned' => false,
        ];
    }
}
