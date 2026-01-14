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
        Schema::create('hr_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_topic_id')->nullable()->constrained('hr_training_topics')->onDelete('set null');
            $table->foreignId('created_by')->constrained('hr_users')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['internal', 'external']); // internal = LGU hosted, external = request
            $table->enum('rank_level', ['all', 'higher', 'normal'])->default('all');
            
            // Training Details
            $table->string('venue')->nullable();
            $table->string('facilitator')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_hours')->nullable();
            
            // External Training Specific
            $table->foreignId('requested_by')->nullable()->constrained('hr_users')->onDelete('set null');
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->date('approved_at')->nullable();
            
            // Status
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
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
        Schema::dropIfExists('hr_trainings');
    }
};
