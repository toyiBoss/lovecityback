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
        Schema::create('user_profile_galleries', function (Blueprint $table) {
            $table->uuid('mediaId')->primary();
            $table->uuid('user_id');
            $table->string('storagePath');
            $table->integer('order')->default(1);
            $table->boolean('isVerified')->default(false);
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile_galleries');
    }
};
