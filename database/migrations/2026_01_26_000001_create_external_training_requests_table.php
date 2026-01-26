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
        Schema::create('external_training_requests', function (Blueprint $table) {
            $table->id();

            // Employee who requested
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // Training details
            $table->string('training_title');
            $table->text('training_description')->nullable();
            $table->string('training_provider')->nullable();
            $table->string('training_venue')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->text('purpose')->nullable();

            // File uploads (3 required files)
            $table->string('request_form_path')->nullable();
            $table->string('request_form_name')->nullable();
            $table->string('department_head_letter_path')->nullable();
            $table->string('department_head_letter_name')->nullable();
            $table->string('requesting_party_document_path')->nullable();
            $table->string('requesting_party_document_name')->nullable();

            // Approval workflow
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('hr_remarks')->nullable();

            // Link to training record if approved
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_training_requests');
    }
};
