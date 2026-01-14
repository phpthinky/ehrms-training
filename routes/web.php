<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmployeeFileController;
use App\Http\Controllers\TrainingSurveyController;
use App\Http\Controllers\PublicFileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // HR Admin & Admin Assistant Routes
    Route::middleware(['role:hr_admin,admin_assistant'])->group(function () {
        
        // Employee Management
        Route::resource('employees', EmployeeController::class);
        
        // Department Management
        Route::resource('departments', DepartmentController::class);
        
        // Training Management
        Route::resource('trainings', TrainingController::class);
        
        // Training Topics
        Route::get('training-topics', function() {
            return redirect()->route('dashboard')->with('info', 'Feature coming soon');
        })->name('training-topics.index');
        
        // Survey Results
        Route::get('surveys', function() {
            return redirect()->route('dashboard')->with('info', 'Feature coming soon');
        })->name('surveys.index');
        
        // Public Files
        Route::resource('public-files', PublicFileController::class);
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        
    });
    
    // Employee Routes
    Route::middleware(['role:employee'])->group(function () {
        Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('my-profile');
        Route::get('/my-trainings', [TrainingController::class, 'myTrainings'])->name('my-trainings');
        Route::get('/my-files', [EmployeeFileController::class, 'myFiles'])->name('my-files');
        
        // Training Survey
        Route::get('/training-survey', [TrainingSurveyController::class, 'show'])->name('training-survey');
    });
    
    // Messages (All authenticated users)
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    Route::post('messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    
    // Notifications (All authenticated users)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Profile & Settings
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('settings', [SettingsController::class, 'show'])->name('settings');
    
});
