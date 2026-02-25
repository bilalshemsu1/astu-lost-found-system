<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->enum('type', ['lost', 'found']);
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_reason')->nullable();
            $table->enum('status', ['pending_verification', 'active', 'returned', 'rejected'])->default('pending_verification');
            $table->string('image_path')->nullable();
            $table->string('location');
            $table->date('item_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
