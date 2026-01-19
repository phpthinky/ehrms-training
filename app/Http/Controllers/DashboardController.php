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

            // Chart Data: Monthly Training Trend (Last 6 months)
            'monthlyTrainingData' => $this->getMonthlyTrainingData(),

            // Chart Data: Department Distribution
            'departmentChartData' => $this->getDepartmentChartData(),

            // Chart Data: Training Attendance Rate
            'attendanceData' => $this->getAttendanceData(),

            // Chart Data: Survey Response Rate
            'surveyResponseData' => $this->getSurveyResponseData(),

            // Recent Activities
            'recentActivities' => $this->getRecentActivities(),
        ];

        return view('dashboard', $data);
    }

    /**
     * Get monthly training data for the last 6 months
     */
    protected function getMonthlyTrainingData()
    {
        $months = [];
        $trainings = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Training::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->count();
            $trainings[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $trainings,
        ];
    }

    /**
     * Get department distribution data
     */
    protected function getDepartmentChartData()
    {
        $departments = Department::withCount('employees')
                                 ->where('is_active', true)
                                 ->orderBy('employees_count', 'desc')
                                 ->limit(8)
                                 ->get();

        return [
            'labels' => $departments->pluck('name')->toArray(),
            'data' => $departments->pluck('employees_count')->toArray(),
        ];
    }

    /**
     * Get training attendance statistics
     */
    protected function getAttendanceData()
    {
        $currentYear = date('Y');
        
        $total = \DB::table('hr_training_attendance')
                    ->whereYear('created_at', $currentYear)
                    ->count();

        if ($total == 0) {
            return ['labels' => [], 'data' => []];
        }

        $attended = \DB::table('hr_training_attendance')
                      ->whereYear('created_at', $currentYear)
                      ->where('attendance_status', 'attended')
                      ->count();

        $absent = \DB::table('hr_training_attendance')
                    ->whereYear('created_at', $currentYear)
                    ->where('attendance_status', 'absent')
                    ->count();

        $registered = \DB::table('hr_training_attendance')
                     ->whereYear('created_at', $currentYear)
                     ->where('attendance_status', 'registered')
                     ->count();

        return [
            'labels' => ['Attended', 'Absent', 'Registered'],
            'data' => [$attended, $absent, $registered],
            'percentage' => $total > 0 ? round(($attended / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get survey response rate data
     */
    protected function getSurveyResponseData()
    {
        $currentYear = date('Y');
        $totalEmployees = Employee::where('status', 'active')->count();
        $submitted = TrainingSurvey::where('year', $currentYear)
                                  ->where('status', 'submitted')
                                  ->count();

        $pending = $totalEmployees - $submitted;

        return [
            'labels' => ['Submitted', 'Pending'],
            'data' => [$submitted, $pending],
            'percentage' => $totalEmployees > 0 ? round(($submitted / $totalEmployees) * 100, 1) : 0,
        ];
    }

    /**
     * Get recent activities
     */
    protected function getRecentActivities()
    {
        $activities = [];

        // Recent trainings
        $recentTrainings = Training::orderBy('created_at', 'desc')
                                  ->limit(3)
                                  ->get();

        foreach ($recentTrainings as $training) {
            $activities[] = [
                'icon' => 'bi-calendar-event',
                'color' => 'primary',
                'title' => 'Training Created',
                'description' => $training->title,
                'time' => $training->created_at->diffForHumans(),
            ];
        }

        // Recent survey submissions
        $recentSurveys = TrainingSurvey::with('employee')
                                      ->where('status', 'submitted')
                                      ->orderBy('updated_at', 'desc')
                                      ->limit(2)
                                      ->get();

        foreach ($recentSurveys as $survey) {
            $activities[] = [
                'icon' => 'bi-clipboard-check',
                'color' => 'success',
                'title' => 'Survey Submitted',
                'description' => $survey->employee->first_name . ' ' . $survey->employee->last_name,
                'time' => $survey->updated_at->diffForHumans(),
            ];
        }

        // Sort by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 5);
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
                'upcomingTrainingAttendance' => collect(),
            ]);
        }

        // Get upcoming trainings employee is registered for
        $upcomingTrainingAttendance = $employee->trainings()
            ->whereHas('training', function($query) {
                $query->whereIn('status', ['upcoming', 'ongoing'])
                      ->where('start_date', '>=', now()->subDays(3)) // Show 3 days before until it ends
                      ->orderBy('start_date', 'asc');
            })
            ->whereIn('attendance_status', ['registered'])
            ->with(['training'])
            ->limit(5)
            ->get();

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
            
            // Upcoming training attendance reminders
            'upcomingTrainingAttendance' => $upcomingTrainingAttendance,
        ];

        return view('dashboard', $data);
    }
}
