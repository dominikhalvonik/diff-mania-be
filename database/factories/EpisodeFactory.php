<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'episode_name' => $this->faker->sentence(2),
            'unlock_stars' => $this->faker->numberBetween(1, 100),
            'unlock_coins' => $this->faker->numberBetween(1, 100),
        ];
    }
}
