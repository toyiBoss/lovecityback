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
        Schema::create('gift_types', function (Blueprint $table) {
    $table->uuid('giftId')->primary();
    $table->string('name');
    $table->unsignedInteger('costInCoins')->default(0);
    $table->string('imageUrl')->nullable();
    $table->boolean('isActive')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_types');
    }
};
