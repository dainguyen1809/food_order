<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DrinkFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1, 10), // Assuming product IDs exist
            'brand' => $this->faker->company(),
            'size' => $this->faker->randomElement(['250ml', '500ml', '1L']),
            'ingredient' => $this->faker->words(3, true),
        ];
    }
}
