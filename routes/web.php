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
use App\Http\Controllers\TrainingAttendanceController;
use App\Http\Controllers\HRDocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\SurveyTemplateController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveyBuilderController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\TrainingTopicController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\ExternalTrainingRequestController;
use App\Http\Controllers\ProfileSettingsController;

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
    
    // Employee Files - View & Download (accessible to HR and Employees)
    Route::get('employees/{employee}/files', [EmployeeFileController::class, 'index'])->name('employee-files.index');
    Route::get('files/{file}/download', [EmployeeFileController::class, 'download'])->name('employee-files.download');

    // Employee Trainings - View trainings attended (accessible to HR and Employees)
    Route::get('employees/{employee}/trainings', [EmployeeController::class, 'trainings'])->name('employees.trainings');

    // External Training Requests - View & Download (accessible to HR and owner)
    // Note: Using regex constraint to avoid conflict with /create route
    Route::get('external-training-requests/{externalTrainingRequest}/download/{type}', [ExternalTrainingRequestController::class, 'downloadDocument'])->name('external-training-requests.download');
    Route::get('external-training-requests/{externalTrainingRequest}', [ExternalTrainingRequestController::class, 'show'])
        ->where('externalTrainingRequest', '[0-9]+')
        ->name('external-training-requests.show');

    // HR Admin & Admin Assistant Routes
    Route::middleware(['role:hr_admin,admin_assistant'])->group(function () {

        // Employee Management
        Route::post('employees/{employee}/resend-password', [EmployeeController::class, 'resendPassword'])->name('employees.resend-password');
        Route::resource('employees', EmployeeController::class);
        
        // Department Management
        Route::resource('departments', DepartmentController::class);
        
        // Employee Files Management (201 Files) - Upload/Create/Delete (HR only)
        Route::get('employees/{employee}/files/create', [EmployeeFileController::class, 'create'])->name('employee-files.create');
        Route::post('employees/{employee}/files', [EmployeeFileController::class, 'store'])->name('employee-files.store');
        Route::delete('files/{file}', [EmployeeFileController::class, 'destroy'])->name('employee-files.destroy');
        
        // Training Management
        Route::get('training-recommendations', [TrainingController::class, 'recommendations'])->name('training-recommendations');
        Route::resource('trainings', TrainingController::class);
        Route::post('trainings/{training}/update-status', [TrainingController::class, 'updateStatus'])->name('trainings.update-status');
        
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
        
        // Training Topics Management
        Route::resource('training-topics', TrainingTopicController::class);
        Route::post('training-topics/{trainingTopic}/toggle-active', [TrainingTopicController::class, 'toggleActive'])->name('training-topics.toggle-active');

        // Training Programs CRUD
        Route::resource('training-programs', TrainingProgramController::class);
        Route::post('training-programs/reorder', [TrainingProgramController::class, 'reorder'])->name('training-programs.reorder');

        // NEW SURVEY SYSTEM (Phase 1 Part 2)
        // Survey Templates Management
        Route::resource('survey-templates', SurveyTemplateController::class);
        Route::post('survey-templates/{surveyTemplate}/toggle-active', [SurveyTemplateController::class, 'toggleActive'])->name('survey-templates.toggle-active');
        Route::post('survey-templates/{surveyTemplate}/duplicate', [SurveyTemplateController::class, 'duplicate'])->name('survey-templates.duplicate');

        // Survey Question Bank
        Route::resource('survey-questions', SurveyQuestionController::class);
        Route::get('survey-questions/{surveyQuestion}/preview', [SurveyQuestionController::class, 'preview'])->name('survey-questions.preview');

        // Survey Builder (Form Builder)
        Route::get('survey-builder/{surveyTemplate}', [SurveyBuilderController::class, 'index'])->name('survey-builder.index');
        Route::post('survey-builder/{surveyTemplate}/add-question', [SurveyBuilderController::class, 'addQuestion'])->name('survey-builder.add-question');
        Route::delete('survey-builder/{surveyTemplate}/questions/{surveyQuestion}', [SurveyBuilderController::class, 'removeQuestion'])->name('survey-builder.remove-question');
        Route::put('survey-builder/{surveyTemplate}/questions/{surveyQuestion}', [SurveyBuilderController::class, 'updateQuestion'])->name('survey-builder.update-question');
        Route::post('survey-builder/{surveyTemplate}/reorder', [SurveyBuilderController::class, 'reorderQuestions'])->name('survey-builder.reorder');
        Route::post('survey-builder/{surveyTemplate}/add-defaults', [SurveyBuilderController::class, 'addDefaultQuestions'])->name('survey-builder.add-defaults');
        Route::get('survey-builder/question/{surveyQuestion}', [SurveyBuilderController::class, 'getQuestion'])->name('survey-builder.get-question');

        // Survey Response Analytics
        Route::get('survey-responses/template/{surveyTemplate}', [SurveyResponseController::class, 'index'])->name('survey-responses.index');
        Route::get('survey-responses/{surveyResponse}', [SurveyResponseController::class, 'show'])->name('survey-responses.show');
        Route::get('survey-analytics/{surveyTemplate}', [SurveyResponseController::class, 'analytics'])->name('survey-responses.analytics');
        Route::get('survey-analytics/{surveyTemplate}/export', [SurveyResponseController::class, 'export'])->name('survey-responses.export');

        // Survey Results (HR View) - OLD SYSTEM
        Route::get('surveys', [TrainingSurveyController::class, 'hrIndex'])->name('surveys.index');
        
        // HR Documents (Secure Files)
        Route::resource('hr-documents', HRDocumentController::class);
        Route::get('hr-documents/{hrDocument}/download', [HRDocumentController::class, 'download'])->name('hr-documents.download');
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/training', [ReportController::class, 'trainingReport'])->name('reports.training');
        Route::get('reports/employee', [ReportController::class, 'employeeReport'])->name('reports.employee');
        Route::get('reports/survey', [ReportController::class, 'surveyReport'])->name('reports.survey');
        Route::get('reports/department', [ReportController::class, 'departmentReport'])->name('reports.department');
        Route::get('reports/export/training', [ReportController::class, 'exportTrainingCSV'])->name('reports.export.training');
        Route::get('reports/export/employee', [ReportController::class, 'exportEmployeeCSV'])->name('reports.export.employee');

        // External Training Requests Management (HR)
        Route::get('external-training-requests', [ExternalTrainingRequestController::class, 'index'])->name('external-training-requests.index');
        Route::get('external-training-requests/{externalTrainingRequest}/review', [ExternalTrainingRequestController::class, 'review'])->name('external-training-requests.review');
        Route::post('external-training-requests/{externalTrainingRequest}/process-review', [ExternalTrainingRequestController::class, 'processReview'])->name('external-training-requests.process-review');
        Route::patch('external-training-requests/{externalTrainingRequest}/mark-completed', [ExternalTrainingRequestController::class, 'markCompleted'])->name('external-training-requests.mark-completed');
    });

    // HR Admin Only Routes (Settings)
    Route::middleware(['role:hr_admin'])->group(function () {
        // System Settings - General
        Route::get('settings/general', [SystemSettingsController::class, 'generalSettings'])->name('settings.general-settings');
        Route::post('settings/general', [SystemSettingsController::class, 'updateGeneralSettings'])->name('settings.general-settings.update');

        // System Settings - 201 Files
        Route::get('settings/201-files', [SystemSettingsController::class, 'fileSettings'])->name('settings.file-settings');
        Route::post('settings/201-files', [SystemSettingsController::class, 'updateFileSettings'])->name('settings.file-settings.update');
    });

    // Employee File Upload (Conditional - based on system setting)
    // These routes are available to employees when setting is enabled
    // Permission check is done inside the controller
    Route::middleware(['role:employee'])->group(function () {
        Route::get('my-files/upload', [EmployeeFileController::class, 'create'])->name('my-files.create')
            ->defaults('employeeId', function() {
                return auth()->user()->employee?->id;
            });
        Route::post('my-files/upload', [EmployeeFileController::class, 'store'])->name('my-files.store')
            ->defaults('employeeId', function() {
                return auth()->user()->employee?->id;
            });
    });
    
    // Employee Routes
    Route::middleware(['role:employee'])->group(function () {
        Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('my-profile');
        Route::get('/my-trainings', [EmployeeController::class, 'myTrainings'])->name('my-trainings');
        Route::get('/my-files', [EmployeeFileController::class, 'myFiles'])->name('my-files');
        
        // Training Survey Form (Employee only can submit) - OLD SYSTEM
        Route::get('/training-survey', [TrainingSurveyController::class, 'form'])->name('training-survey.form');
        Route::post('/training-survey', [TrainingSurveyController::class, 'submit'])->name('training-survey.submit');

        // NEW SURVEY SYSTEM - Employee Survey Submission
        Route::get('/survey', [SurveyResponseController::class, 'showForm'])->name('survey.form');
        Route::post('/survey/submit', [SurveyResponseController::class, 'submit'])->name('survey.submit');
        Route::post('/survey/draft', [SurveyResponseController::class, 'saveDraft'])->name('survey.draft');

        // External Training Requests (Employee)
        Route::get('/my-external-training-requests', [ExternalTrainingRequestController::class, 'myRequests'])->name('my-external-training-requests');
        Route::get('/external-training-requests/create', [ExternalTrainingRequestController::class, 'create'])->name('external-training-requests.create');
        Route::post('/external-training-requests', [ExternalTrainingRequestController::class, 'store'])->name('external-training-requests.store');
        Route::get('/external-training-requests/{externalTrainingRequest}/edit', [ExternalTrainingRequestController::class, 'edit'])->name('external-training-requests.edit');
        Route::put('/external-training-requests/{externalTrainingRequest}', [ExternalTrainingRequestController::class, 'update'])->name('external-training-requests.update');
        Route::delete('/external-training-requests/{externalTrainingRequest}', [ExternalTrainingRequestController::class, 'destroy'])->name('external-training-requests.destroy');
    });
    
    // Training Surveys (Both HR and Employees can view)
    Route::middleware(['role:hr_admin,admin_assistant,employee'])->group(function () {
        Route::get('training-surveys', [TrainingSurveyController::class, 'index'])->name('training-surveys.index');
        Route::get('training-surveys/{survey}', [TrainingSurveyController::class, 'show'])->name('training-surveys.show');
    });
    
    // Messages (All authenticated users)
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    Route::post('messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    
    // Notifications (All authenticated users)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Profile & Account Settings
    Route::get('profile', [ProfileSettingsController::class, 'profile'])->name('profile');
    Route::get('settings', [ProfileSettingsController::class, 'settings'])->name('settings');
    Route::put('settings/name', [ProfileSettingsController::class, 'updateName'])->name('settings.update-name');
    Route::put('settings/email', [ProfileSettingsController::class, 'updateEmail'])->name('settings.update-email');
    Route::put('settings/password', [ProfileSettingsController::class, 'updatePassword'])->name('settings.update-password');

    // Help & Guide (All authenticated users)
    Route::get('help', function () {
        return view('help.index');
    })->name('help');

});
