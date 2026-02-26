<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telegram_chat_id')->nullable()->unique();
            $table->string('telegram_username')->nullable();
            $table->string('telegram_verification_code')->nullable()->unique();
            $table->timestamp('telegram_verified_at')->nullable();
            $table->enum('role', ['student', 'admin'])->default('student');
            $table->integer('trust_score')->default(0);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
