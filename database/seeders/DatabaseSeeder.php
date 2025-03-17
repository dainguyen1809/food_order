<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\Discount;
use App\Models\Drink;
use App\Models\Food;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory()->create([
        //     'name' => 'DHN',
        //     'email' => 'dainguyen.nhd@gmail.com',
        // ]);

        ApiKey::factory(1)->create();
        User::factory(10)->create();

        Product::factory(10)->create();

        // Create other tables
        Discount::factory(10)->create();
        Inventory::factory(10)->create();
        Food::factory(10)->create();
        Drink::factory(10)->create();


    }
}
