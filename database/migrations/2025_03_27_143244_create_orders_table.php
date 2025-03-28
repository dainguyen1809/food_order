<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('order_checkout')->nullable();
            $table->json('order_payment')->nullable();
            $table->json('order_shipping')->nullable();
            $table->string('order_tracking')->default('#000000270302025');
            $table->json('order_products');
            $table->string('order_status')->default(OrderStatus::PENDING);
            $table->timestamp('created_on')->nullable();
            $table->timestamp('modified_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
