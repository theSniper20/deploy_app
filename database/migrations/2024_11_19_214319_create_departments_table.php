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
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade'); // Foreign key to hospitals table
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
        Schema::dropIfExists('departments');
    }
    
};
