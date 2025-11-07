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
Schema::create('lives', function (Blueprint $table) {
    $table->uuid('liveId')->primary();
    $table->uuid('hostId');
    $table->string('title')->nullable();
    $table->string('status')->default('SCHEDULED'); // LIVE, ENDED, SCHEDULED
    $table->string('streamUrl')->nullable();
    $table->unsignedInteger('viewersCount')->default(0);
    $table->timestamp('startTime')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lives');
    }
};
