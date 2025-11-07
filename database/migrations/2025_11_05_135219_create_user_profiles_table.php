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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('name')->unique();
            $table->string('firstName')->nullable();
            $table->string('bio')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->string('country_id')->nullable();
            $table->string('city')->nullable();
            $table->string('galleryRef')->nullable();
            $table->string('settingsRef')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
