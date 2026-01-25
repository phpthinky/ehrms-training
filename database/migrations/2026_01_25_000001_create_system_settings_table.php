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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('category')->nullable(); // Group settings by category
            $table->string('label')->nullable(); // Human-readable label
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'allow_employee_file_upload',
                'value' => '0', // 0 = disabled by default, 1 = enabled
                'type' => 'boolean',
                'category' => '201_files',
                'label' => 'Allow employees to upload their own 201 files',
                'description' => 'When enabled, employees can upload documents to their own 201 files. When disabled, only HR staff can upload.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
