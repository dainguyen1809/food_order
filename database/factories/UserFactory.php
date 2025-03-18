<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Enums\RoleShop; // Ensure this exists or replace it with string roles like 'shop' or 'admin'.

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'avatar' => $this->faker->imageUrl(100, 100, 'people'), // Generates random avatar URL
            'email' => $this->faker->unique()->safeEmail(),
            'verify' => $this->faker->boolean(30), // 30% chance of being verified
            'password' => Hash::make('password'), // Default password
            'roles' => RoleShop::SHOP ?? 'shop', // Replace `RoleShop::SHOP` if not defined
            'remember_token' => Str::random(10),
        ];
    }
}
