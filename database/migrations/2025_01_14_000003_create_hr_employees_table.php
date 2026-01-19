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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            
            // Personal Information
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('civil_status')->nullable();
            $table->text('address')->nullable();
            
            // Employment Details
            $table->string('position');
            $table->enum('rank_level', ['higher', 'normal'])->default('normal');
            $table->enum('employment_type', ['permanent', 'job_order', 'contract']);
            $table->date('date_hired')->nullable();
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            
            // Contact Information
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            
            // Additional Fields
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
