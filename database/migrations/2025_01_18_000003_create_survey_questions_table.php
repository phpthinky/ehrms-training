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
        Schema::create('hr_survey_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('question_type', [
                'training_programs',  // Special: checkboxes from training_programs table
                'checkbox',           // Multiple checkboxes
                'radio',              // Single choice
                'text',               // Short text input
                'textarea',           // Long text input
                'number',             // Number input
                'scale'               // Rating scale 1-5
            ]);
            $table->json('options')->nullable(); // For checkbox/radio options
            $table->text('help_text')->nullable();
            $table->boolean('is_default')->default(false); // From PDF template
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_survey_questions');
    }
};
