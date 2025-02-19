<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_thumb');
            $table->text('product_description')->nullable();
            $table->decimal('product_price', 10, 2);
            $table->integer('product_quantity');
            $table->string('product_type'); // food, drink, dessert, etc
            $table->foreignId('product_shop')->constrained('users')->onDelete('cascade');
            $table->json('product_attributes');
            $table->timestamps();
        });

        Schema::create('food', function (Blueprint $table) {
            $table->string('ingredient');
            $table->string('spiciness');
            $table->string('size');
            $table->timestamps();
        });

        Schema::create('drink', function (Blueprint $table) {
            $table->string('brand');
            $table->string('size');
            $table->string('ingredient');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('food');
        Schema::dropIfExists('drink');
    }
};
