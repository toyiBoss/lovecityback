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
Schema::create('settings', function (Blueprint $table) {
    $table->uuid('userId')->primary(); // one row per user
    $table->json('searchGender')->nullable();
    $table->integer('minAge')->nullable();
    $table->integer('maxAge')->nullable();
    $table->integer('maxDistanceKm')->nullable();
    $table->boolean('isVisible')->default(true);
    $table->json('notificationPrefs')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
