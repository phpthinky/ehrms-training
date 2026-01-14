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
        Schema::create('hr_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // EHRMS Custom Fields
            $table->enum('role', ['hr_admin', 'admin_assistant', 'employee', 'guest'])->default('employee');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->string('employee_id')->nullable()->unique();
            $table->string('contact_number')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->enum('employment_type', ['permanent', 'job_order', 'contract'])->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('hr_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('hr_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_users');
        Schema::dropIfExists('hr_password_reset_tokens');
        Schema::dropIfExists('hr_sessions');
    }
};
