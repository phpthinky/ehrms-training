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
        Schema::create('hr_training_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('hr_trainings')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('hr_employees')->onDelete('cascade');
            
            $table->enum('attendance_status', ['registered', 'attended', 'absent', 'excused'])->default('registered');
            $table->boolean('certificate_uploaded')->default(false);
            $table->foreignId('certificate_file_id')->nullable()->constrained('hr_employee_files')->onDelete('set null');
            
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
        Schema::dropIfExists('hr_training_attendance');
    }
};
