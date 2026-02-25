<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // The found item
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The claimant
            $table->float('similarity_score');
            $table->text('proof');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('admin_decision', ['approved', 'rejected'])->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
