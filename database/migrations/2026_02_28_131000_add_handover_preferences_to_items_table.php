<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'share_phone')) {
                $table->boolean('share_phone')->default(false)->after('status');
            }

            if (!Schema::hasColumn('items', 'share_telegram')) {
                $table->boolean('share_telegram')->default(false)->after('share_phone');
            }

            if (!Schema::hasColumn('items', 'return_location_preference')) {
                $table->string('return_location_preference', 40)->nullable()->after('share_telegram');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'return_location_preference')) {
                $table->dropColumn('return_location_preference');
            }

            if (Schema::hasColumn('items', 'share_telegram')) {
                $table->dropColumn('share_telegram');
            }

            if (Schema::hasColumn('items', 'share_phone')) {
                $table->dropColumn('share_phone');
            }
        });
    }
};
