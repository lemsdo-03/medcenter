<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FR-43 + FR-48: a dispense is one invoice (header) created when medicines are handed out
    public function up(): void
    {
        Schema::create('dispenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete(); // FR-47 prescription owner
            $table->foreignId('medical_note_id')->nullable()->constrained('medical_notes')->nullOnDelete(); // source prescription (FR-42)
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->nullOnDelete(); // who dispensed
            $table->decimal('total', 10, 2)->default(0); // invoice total
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispenses');
    }
};
