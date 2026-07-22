<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedInteger('visit_count')->default(0); // FR-70 counts completed visits
            $table->boolean('reward_available')->default(false); // FR-71 reward earned on every 4th visit
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['visit_count', 'reward_available']);
        });
    }
};
