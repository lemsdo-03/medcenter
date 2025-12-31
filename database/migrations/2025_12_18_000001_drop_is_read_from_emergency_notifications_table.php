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
        Schema::table('emergency_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('emergency_notifications', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('emergency_notifications', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('emergency_notifications', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
            if (!Schema::hasColumn('emergency_notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }
        });
    }
};


