<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarPhoto>
 */
class CarPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDigits = $this->faker->randomNumber(11);
        $content = $randomDigits . '.png';
        
        return [
            'car_id' => \App\Models\Car::factory(),
            'content' => $content,
        ];
    }
}
