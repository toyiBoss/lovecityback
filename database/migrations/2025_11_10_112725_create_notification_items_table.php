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
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('notificationId')->primary();
    $table->uuid('userId')->index();
    $table->string('type');
    $table->text('message')->nullable();
    $table->string('relatedEntityId')->nullable();
    $table->timestamp('timestamp')->nullable();
    $table->boolean('isRead')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_items');
    }
};
