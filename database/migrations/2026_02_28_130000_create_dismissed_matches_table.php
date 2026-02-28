<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dismissed_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('similarity_log_id')->constrained('similarity_logs')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'similarity_log_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dismissed_matches');
    }
};

