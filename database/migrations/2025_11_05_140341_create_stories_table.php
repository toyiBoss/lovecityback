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
        Schema::create('stories', function (Blueprint $table) {
            $table->uuid('storyId')->primary();
            $table->uuid('userId');
            $table->string('storagePath')->nullable();
            $table->string('type');
            $table->string('caption')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamp('expiresAt')->nullable();
            $table->json('views')->nullable(); // map des vues
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
