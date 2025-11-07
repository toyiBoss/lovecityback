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
Schema::create('carousel_items', function (Blueprint $table) {
    $table->uuid('itemId')->primary();
    $table->string('title')->nullable();
    $table->string('imageUrl')->nullable();
    $table->string('linkUrl')->nullable();
    $table->integer('order')->default(1);
    $table->boolean('isActive')->default(true);
    $table->string('targetCountryId', 2)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_items');
    }
};
