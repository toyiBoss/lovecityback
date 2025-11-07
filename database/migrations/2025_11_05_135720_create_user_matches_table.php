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
        Schema::create('user_matches', function (Blueprint $table) {
            $table->uuid('matchId')->primary();
            $table->uuid('user1Id');
            $table->uuid('user2Id');
            $table->timestamp('timestamp')->nullable();
            $table->string('discussionRef')->nullable();
            $table->timestamp('lastMessageTimestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_matches');
    }
};
