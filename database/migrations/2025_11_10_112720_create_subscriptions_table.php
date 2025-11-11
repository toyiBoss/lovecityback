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
Schema::create('subscriptions', function (Blueprint $table) {
    $table->uuid('subscriptionId')->primary();
    $table->string('name');
    $table->integer('durationDays')->default(30);
    $table->decimal('price', 10, 2)->default(0);
    $table->json('features')->nullable();
    $table->string('storeProductId')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
