<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FoodFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1, 10), // Assuming product IDs exist
            'ingredient' => $this->faker->words(3, true),
            'spiciness' => $this->faker->randomElement(['Mild', 'Medium', 'Spicy']),
            'size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
        ];
    }
}
