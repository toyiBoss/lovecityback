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
Schema::create('invitations', function (Blueprint $table) {
    $table->uuid('invitationId')->primary();
    $table->uuid('senderId')->index();
    $table->string('recipientContact'); // email or phone
    $table->string('code')->unique();
    $table->string('status')->default('SENT'); // SENT, PENDING, ACCEPTED
    $table->timestamp('timestamp')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
