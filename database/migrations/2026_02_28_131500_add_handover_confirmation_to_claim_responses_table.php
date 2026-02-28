<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claim_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('claim_responses', 'handover_confirmed_at')) {
                $table->timestamp('handover_confirmed_at')->nullable()->after('response_at');
            }

            if (!Schema::hasColumn('claim_responses', 'confirmed_by_admin_id')) {
                $table->foreignId('confirmed_by_admin_id')
                    ->nullable()
                    ->after('handover_confirmed_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('claim_responses', function (Blueprint $table) {
            if (Schema::hasColumn('claim_responses', 'confirmed_by_admin_id')) {
                $table->dropConstrainedForeignId('confirmed_by_admin_id');
            }

            if (Schema::hasColumn('claim_responses', 'handover_confirmed_at')) {
                $table->dropColumn('handover_confirmed_at');
            }
        });
    }
};
