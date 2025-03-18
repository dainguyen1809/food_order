<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'product_name' => $this->faker->word().' Product',
            'product_thumb' => 'https://example.com/images/pizza.jpg',
            'product_description' => $this->faker->sentence(),
            'product_slug' => Str::slug($this->faker->word().'-'.$this->faker->unique()->randomNumber(5)),
            'product_rating' => $this->faker->randomFloat(1, 3, 5),
            'isDraft' => $this->faker->boolean(),
            'isPublished' => $this->faker->boolean(),
            'product_price' => $this->faker->randomFloat(2, 1, 100),
            'product_quantity' => $this->faker->numberBetween(10, 100),
            'product_type' => $this->faker->randomElement(['food', 'drink', 'dessert']),
            'product_shop' => 1, // Assuming shop ID 1 exists
            'product_attributes' => json_encode(['weight' => '200g', 'calories' => $this->faker->numberBetween(100, 500)]),
            'product_variation' => json_encode(['size' => ['small', 'medium', 'large']]),
        ];
    }
}
