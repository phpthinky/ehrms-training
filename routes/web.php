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
use App\Http\Controllers\EmployeeFileController;
use App\Http\Controllers\TrainingAttendanceController;
use App\Http\Controllers\HRDocumentController;
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
        
        // Employee Files Management (201 Files)
        Route::get('employees/{employee}/files', [EmployeeFileController::class, 'index'])->name('employee-files.index');
        Route::get('employees/{employee}/files/create', [EmployeeFileController::class, 'create'])->name('employee-files.create');
        Route::post('employees/{employee}/files', [EmployeeFileController::class, 'store'])->name('employee-files.store');
        Route::get('files/{file}/download', [EmployeeFileController::class, 'download'])->name('employee-files.download');
        Route::delete('files/{file}', [EmployeeFileController::class, 'destroy'])->name('employee-files.destroy');
        
        // Training Management
        Route::resource('trainings', TrainingController::class);
        
        // Training Attendance Management
        Route::post('trainings/{training}/attendance', [TrainingAttendanceController::class, 'store'])->name('trainings.attendance.add');
        Route::put('trainings/attendance/{attendance}/status', [TrainingAttendanceController::class, 'updateStatus'])->name('trainings.attendance.update-status');
        Route::post('trainings/attendance/{attendance}/certificate', [TrainingAttendanceController::class, 'uploadCertificate'])->name('trainings.attendance.upload-certificate');
        Route::get('trainings/attendance/{attendance}/certificate/download', [TrainingAttendanceController::class, 'downloadCertificate'])->name('trainings.attendance.download-certificate');
        Route::delete('trainings/attendance/{attendance}', [TrainingAttendanceController::class, 'destroy'])->name('trainings.attendance.destroy');
        Route::post('trainings/{training}/attendance/bulk', [TrainingAttendanceController::class, 'bulkUpdate'])->name('trainings.attendance.bulk-update');
        Route::post('trainings/{training}/attendance/mark-all-attended', [TrainingAttendanceController::class, 'markAllAttended'])->name('trainings.attendance.mark-all-attended');
        Route::post('trainings/{training}/attendance/notify', [TrainingAttendanceController::class, 'sendNotifications'])->name('trainings.attendance.notify');
        Route::get('trainings/{training}/attendance/eligible', [TrainingAttendanceController::class, 'getEligibleEmployees'])->name('trainings.attendance.eligible');
        Route::get('trainings/{training}/attendance/export', [TrainingAttendanceController::class, 'exportAttendance'])->name('trainings.attendance.export');
        
        // Training Topics
        Route::get('training-topics', function() {
            return redirect()->route('dashboard')->with('info', 'Feature coming soon');
        })->name('training-topics.index');
        
        // Survey Results
        Route::get('surveys', function() {
            return redirect()->route('dashboard')->with('info', 'Feature coming soon');
        })->name('surveys.index');
        
        // HR Documents (Secure Files)
        Route::resource('hr-documents', HRDocumentController::class);
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        
    });
    
    // Employee Routes
    Route::middleware(['role:employee'])->group(function () {
        Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('my-profile');
        Route::get('/my-trainings', [TrainingController::class, 'myTrainings'])->name('my-trainings');
        Route::get('/my-files', [EmployeeFileController::class, 'myFiles'])->name('my-files');
        
        // Training Surveys
        Route::get('training-surveys', [TrainingSurveyController::class, 'index'])->name('training-surveys.index');
        Route::get('training-surveys/{survey}', [TrainingSurveyController::class, 'show'])->name('training-surveys.show');
        
        // Training Survey (Employee)
        Route::get('/training-survey', [TrainingSurveyController::class, 'form'])->name('training-survey.form');
        Route::post('/training-survey', [TrainingSurveyController::class, 'submit'])->name('training-survey.submit');
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
