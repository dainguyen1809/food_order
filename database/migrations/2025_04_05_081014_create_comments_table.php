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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_productId')->nullable()->constrained('products')->onDelete('cascade');
            $table->unsignedBigInteger('comment_userId');
            $table->text('comment_content');
            $table->integer('comment_left')->default(0);
            $table->integer('comment_right')->default(0);
            $table->integer('comment_parentId')->nullable()->constrained('comments')->onDelete('cascade');
            $table->boolean('isDelete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
