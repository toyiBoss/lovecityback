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
Schema::create('payments', function (Blueprint $table) {
    $table->uuid('paymentId')->primary();
    $table->uuid('userId')->index();
    $table->string('type'); // SUBSCRIPTION, COINS_PURCHASE
    $table->decimal('amount', 12, 2);
    $table->string('currency', 8)->default('USD');
    $table->string('productId')->nullable();
    $table->timestamp('timestamp')->nullable();
    $table->text('receiptData')->nullable();
    $table->string('status')->default('PENDING'); // SUCCESS, PENDING, FAILED
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
