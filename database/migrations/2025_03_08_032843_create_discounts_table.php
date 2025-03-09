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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_name');
            $table->text('discount_description');
            $table->string('discount_code');
            $table->string('discount_type')->default('fixed_amount');
            $table->string('discount_value');
            $table->dateTime('discount_start_date');
            $table->dateTime('discount_end_date');
            $table->integer('discount_max_uses');
            $table->integer('discount_uses_count')->default(0);
            $table->json('discount_users_used')->nullable();
            $table->integer('discount_min_orders_value');
            $table->integer('discount_max_uses_per_user');
            $table->integer('discount_max_value');
            $table->foreignId('discount_shop')->constrained('users')->onDelete('cascade');
            $table->boolean('discount_is_active')->default(0); //false
            $table->string('discount_applies_to')->nullable();
            $table->json('discount_product_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
