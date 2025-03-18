<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiscountFactory extends Factory
{
    public function definition()
    {
        return [
            'discount_name' => $this->faker->word().' Discount',
            'discount_description' => $this->faker->sentence(),
            'discount_code' => strtoupper(Str::random(10)),
            'discount_type' => $this->faker->randomElement(['fixed_amount', 'percentage']),
            'discount_value' => $this->faker->numberBetween(5, 50),
            'discount_start_date' => Carbon::now(),
            'discount_end_date' => Carbon::now()->addDays($this->faker->numberBetween(10, 30)),
            'discount_max_uses' => $this->faker->numberBetween(50, 200),
            'discount_uses_count' => 0,
            'discount_users_used' => json_encode([]),
            'discount_min_orders_value' => $this->faker->numberBetween(10, 100),
            'discount_max_uses_per_user' => $this->faker->numberBetween(1, 5),
            'discount_max_value' => $this->faker->numberBetween(50, 500),
            'discount_shop' => 1, // Assuming shop ID 1 exists
            'discount_is_active' => $this->faker->boolean(),
            'discount_applies_to' => 'all',
            'discount_product_ids' => json_encode([]),
        ];
    }
}
