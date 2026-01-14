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
        Schema::create('hr_employee_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('hr_employees')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('hr_users')->onDelete('cascade');
            
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // e.g., certificate, transcript, ID, training_cert
            $table->string('file_category')->nullable(); // personal, training, compliance
            $table->integer('file_size')->nullable(); // in bytes
            $table->text('description')->nullable();
            
            $table->enum('visibility', ['hr_only', 'employee_view', 'public'])->default('hr_only');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employee_files');
    }
};
