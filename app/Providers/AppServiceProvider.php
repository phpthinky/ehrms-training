<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\EmployeeFile;
use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Observers\EmployeeFileObserver;
use App\Observers\TrainingObserver;
use App\Observers\TrainingAttendanceObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for automated notifications
        EmployeeFile::observe(EmployeeFileObserver::class);
        Training::observe(TrainingObserver::class);
        TrainingAttendance::observe(TrainingAttendanceObserver::class);
    }
}
