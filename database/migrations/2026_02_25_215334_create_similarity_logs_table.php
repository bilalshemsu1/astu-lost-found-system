<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('similarity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('found_item_id')->constrained('items')->onDelete('cascade');
            $table->float('similarity_percentage');
            $table->float('title_match');
            $table->float('category_match');
            $table->float('description_match');
            $table->float('location_match');
            $table->float('date_match');
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('similarity_logs');
    }
};
