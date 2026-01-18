<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NotificationController;

class NotifySurveyDue extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notify:survey-due {year?}';

    /**
     * The console command description.
     */
    protected $description = 'Send notifications to all active employees about pending training survey';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');

        $this->info("Sending survey notifications for year {$year}...");

        try {
            $count = NotificationController::notifySurveyDue($year);
            $this->info("✓ Successfully sent {$count} notifications.");
            return 0;
        } catch (\Exception $e) {
            $this->error("✗ Failed to send notifications: " . $e->getMessage());
            return 1;
        }
    }
}
