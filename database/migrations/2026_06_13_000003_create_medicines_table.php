<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            // FR-50: a medicine belongs to a category (kept nullable so deleting a category does not delete medicines)
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique(); // FR-41: searchable unique code
            $table->unsignedInteger('quantity')->default(0); // FR-40 available stock
            $table->unsignedInteger('min_quantity')->default(10); // FR-44 low-stock threshold
            $table->decimal('price', 8, 2)->default(0); // FR-48 invoice price
            $table->date('expiry_date')->nullable(); // FR-45 expiration date
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
