<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->boolean('finder_responded')->default(false);
            $table->boolean('finder_shares_contact')->nullable();
            $table->timestamp('response_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_responses');
    }
};
