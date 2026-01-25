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
        // Insert general system settings
        DB::table('system_settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'EHRMS',
                'type' => 'string',
                'category' => 'general',
                'label' => 'Application Name',
                'description' => 'The full name of the application displayed in the header and title.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_short_name',
                'value' => 'EHRMS',
                'type' => 'string',
                'category' => 'general',
                'label' => 'Application Short Name',
                'description' => 'Short name displayed in the sidebar and compact areas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_logo',
                'value' => '',
                'type' => 'string',
                'category' => 'general',
                'label' => 'Application Logo',
                'description' => 'Path to the uploaded logo file. Displayed in header and login page.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_tagline',
                'value' => 'Employee Human Resource Management System',
                'type' => 'string',
                'category' => 'general',
                'label' => 'Application Tagline',
                'description' => 'Tagline or subtitle displayed on login page.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'organization_name',
                'value' => 'LGU Sablayan',
                'type' => 'string',
                'category' => 'general',
                'label' => 'Organization Name',
                'description' => 'Your organization or company name.',
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
        DB::table('system_settings')->whereIn('key', [
            'app_name',
            'app_short_name',
            'app_logo',
            'app_tagline',
            'organization_name',
        ])->delete();
    }
};
