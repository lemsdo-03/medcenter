<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FR-43/46/48/51: one line per medicine dispensed (drives the invoice and the sales reports)
    public function up(): void
    {
        Schema::create('dispense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispense_id')->constrained('dispenses')->cascadeOnDelete();
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            $table->string('medicine_name'); // kept so the invoice still reads correctly if a medicine is later deleted
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispense_items');
    }
};
