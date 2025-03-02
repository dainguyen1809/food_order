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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inven_shopID')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('inven_productID')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('inven_location')->default('unknown');
            $table->integer('inven_stock');
            $table->json('inven_reservation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
