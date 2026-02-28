<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            if (!Schema::hasColumn('claims', 'similarity_log_id')) {
                $table->foreignId('similarity_log_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('similarity_logs')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('claims', 'similarity_details')) {
                $table->json('similarity_details')
                    ->nullable()
                    ->after('similarity_score');
            }
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            if (Schema::hasColumn('claims', 'similarity_log_id')) {
                $table->dropConstrainedForeignId('similarity_log_id');
            }

            if (Schema::hasColumn('claims', 'similarity_details')) {
                $table->dropColumn('similarity_details');
            }
        });
    }
};
