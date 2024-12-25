<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define a factory which will randomly append a level to an episode
        return [
            'number' => $this->faker->numberBetween(1, 100),
            'difficulty' => $this->faker->randomDigit(),
        ];
    }
}
