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
        Schema::create('training_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            $table->enum('attendance_status', ['registered', 'attended', 'absent', 'excused'])->default('registered');
            $table->boolean('certificate_uploaded')->default(false);
            $table->foreignId('certificate_file_id')->nullable()->constrained('employee_files')->onDelete('set null');
            
            $table->text('remarks')->nullable();
            $table->timestamp('attended_at')->nullable();
            
            $table->timestamps();
            
            // Prevent duplicate attendance entries
            $table->unique(['training_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_attendance');
    }
};
