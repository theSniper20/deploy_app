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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id') // Foreign key referencing departments
            ->constrained()
            ->onDelete('cascade'); // Cascade delete if a department is deleted
            $table->string('name', 100)->unique(); // VARCHAR(100), NOT NULL, UNIQUE
            $table->text('description')->nullable(); // TEXT, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
