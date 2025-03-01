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
            $table->string('product_slug');
            $table->double('product_rating')->default(4.5);
            $table->boolean('isDraft')->default(1);
            $table->boolean('isPublished')->default(0);
            $table->decimal('product_price', 10, 2);
            $table->integer('product_quantity');
            $table->string('product_type'); // food, drink, dessert, etc
            $table->foreignId('product_shop')->nullable()->constrained('users')->onDelete('cascade');
            $table->json('product_attributes');
            $table->json('product_variation')->nullable();
            $table->timestamps();
        });

        Schema::create('food', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable();
            $table->string('ingredient');
            $table->string('spiciness');
            $table->string('size');
            $table->timestamps();
            $table->foreign('id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('drink', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable();
            $table->string('brand');
            $table->string('size');
            $table->string('ingredient');
            $table->timestamps();
            $table->foreign('id')->references('id')->on('products')->onDelete('cascade');
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
