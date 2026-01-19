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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['policy', 'memo', 'form', 'guideline', 'manual', 'template', 'report', 'letter', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->decimal('file_size', 10, 2)->nullable(); // in KB
            $table->string('file_type', 10)->nullable();
            $table->boolean('is_confidential')->default(false);
            $table->date('effective_date')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
