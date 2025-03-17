<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    public function definition()
    {
        return [
            'inven_shopID' => 1, // Assuming shop ID 1 exists
            'inven_productID' => $this->faker->numberBetween(1, 10),
            'inven_location' => $this->faker->city(),
            'inven_stock' => $this->faker->numberBetween(0, 500),
            'inven_reservation' => json_encode([]),
        ];
    }
}
