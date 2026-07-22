<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FR-49: log of damaged or returned medicine quantities (each row reduces sellable stock)
    public function up(): void
    {
        Schema::create('medicine_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type'); // 'damaged' or 'returned'
            $table->unsignedInteger('quantity');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_adjustments');
    }
};
