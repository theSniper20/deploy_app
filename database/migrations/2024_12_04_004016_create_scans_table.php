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
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id') 
            ->constrained()
            ->onDelete('cascade');// Foreign key referencing category
            $table->foreignId('service_id') // Foreign key referencing service
            ->constrained()
            ->onDelete('cascade'); // Cascade delete if a department is deleted
            $table->string('image_name', 100); // VARCHAR(100), NOT NULL, UNIQUE
            $table->text('path_name'); // TEXT
            $table->enum('display_method', ['paper', 'screen'])->default('paper');
            $table->string('scan_reference');
            $table->date('produced_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
