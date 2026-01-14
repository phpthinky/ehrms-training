<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingSurvey;
use App\Models\Department;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isStaff()) {
            return $this->staffDashboard();
        }

        return $this->employeeDashboard();
    }

    /**
     * Staff (HR Admin / Admin Assistant) Dashboard
     */
    protected function staffDashboard()
    {
        $currentYear = date('Y');

        $data = [
            // Statistics
            'totalEmployees' => Employee::count(),
            'activeEmployees' => Employee::where('status', 'active')->count(),
            'totalTrainings' => Training::whereYear('created_at', $currentYear)->count(),
            'upcomingTrainings' => Training::where('status', 'scheduled')
                                          ->where('start_date', '>=', now())
                                          ->count(),
            'surveyResponses' => TrainingSurvey::where('year', $currentYear)
                                              ->where('status', 'submitted')
                                              ->count(),
            'pendingRequests' => Training::where('type', 'external')
                                        ->where('approval_status', 'pending')
                                        ->count(),

            // Upcoming Trainings List
            'upcomingTrainingsList' => Training::with(['attendances'])
                                              ->where('status', 'scheduled')
                                              ->where('start_date', '>=', now())
                                              ->orderBy('start_date', 'asc')
                                              ->limit(5)
                                              ->get(),

            // Department Statistics
            'departmentStats' => Department::withCount('employees')
                                          ->where('is_active', true)
                                          ->orderBy('employees_count', 'desc')
                                          ->limit(6)
                                          ->get(),

            // Unread counts
            'unreadMessages' => Message::where('receiver_id', auth()->id())
                                      ->where('is_read', false)
                                      ->count(),
            'unreadNotifications' => Notification::where('user_id', auth()->id())
                                                 ->where('is_read', false)
                                                 ->count(),
        ];

        return view('dashboard', $data);
    }

    /**
     * Employee Dashboard
     */
    protected function employeeDashboard()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return view('dashboard', [
                'myTrainingsCount' => 0,
                'myFilesCount' => 0,
                'myPendingRequests' => 0,
                'myUnreadMessages' => 0,
            ]);
        }

        $data = [
            // Employee Statistics
            'myTrainingsCount' => $employee->trainings()
                                          ->where('attendance_status', 'attended')
                                          ->count(),
            'myFilesCount' => $employee->files()->count(),
            'myPendingRequests' => Training::where('type', 'external')
                                          ->where('requested_by', auth()->id())
                                          ->where('approval_status', 'pending')
                                          ->count(),
            'myUnreadMessages' => Message::where('receiver_id', auth()->id())
                                        ->where('is_read', false)
                                        ->count(),

            // Unread notifications
            'unreadNotifications' => Notification::where('user_id', auth()->id())
                                                 ->where('is_read', false)
                                                 ->count(),
        ];

        return view('dashboard', $data);
    }
}
