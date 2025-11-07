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
        Schema::create('discussions', function (Blueprint $table) {
    $table->uuid('discussionId')->primary();
    $table->uuid('user1Id');
    $table->uuid('user2Id');
    $table->string('status')->default('ACTIVE'); // ACTIVE, BLOCKED, ARCHIVED
    $table->text('lastMessage')->nullable();
    $table->timestamp('lastMessageTimestamp')->nullable();
    $table->integer('unreadCount_U1')->default(0);
    $table->integer('unreadCount_U2')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};
