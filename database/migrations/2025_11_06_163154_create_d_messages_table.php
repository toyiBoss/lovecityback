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
Schema::create('d_messages', function (Blueprint $table) {
    $table->uuid('messageId')->primary();
    $table->uuid('discussionId');
    $table->uuid('senderId');
    $table->text('text')->nullable();
    $table->string('mediaUrl')->nullable();
    $table->string('type')->default('TEXT'); // TEXT, IMAGE, GIFT, ...
    $table->timestamp('timestamp')->nullable();
    $table->boolean('isSeen')->default(false);
    $table->timestamps();

    $table->index('discussionId');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_messages');
    }
};
