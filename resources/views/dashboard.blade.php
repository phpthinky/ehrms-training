@extends('layouts.app')

@section('title', 'Dashboard - EHRMS')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .activity-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .activity-icon {
        position: absolute;
        left: -30px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        border: 2px solid;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
    }
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);">
                <div class="card-body p-4 text-dark">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 700;">
                                Welcome back, {{ auth()->user()->name }}! 👋
                            </h3>
                            <p class="mb-0 opacity-75">
                                @if(auth()->user()->isHRAdmin())
                                    HR Administrator Dashboard - Manage employees, trainings, and system operations
                                @elseif(auth()->user()->isAdminAssistant())
                                    Admin Assistant Dashboard - Support HR operations and employee management
                                @else
                                    Employee Dashboard - View your profile, trainings, and updates
                                @endif
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-speedometer2" style="font-size: 4rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isStaff())
        <!-- HR/Admin Dashboard -->
        
        <!-- Stats Cards Row 1 -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card shadow-sm" style="border-left: 4px solid #3b82f6;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Total Employees</p>
                                <h2 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #3b82f6;">
                                    {{ $totalEmployees ?? 0 }}
                                </h2>
                            </div>
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-people-fill" style="font-size: 1.5rem; color: #3b82f6;"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> {{ $activeEmployees ?? 0 }} active
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card shadow-sm" style="border-left: 4px solid #8b5cf6;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Trainings This Year</p>
                                <h2 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #8b5cf6;">
                                    {{ $totalTrainings ?? 0 }}
                                </h2>
                            </div>
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(139, 92, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-journal-bookmark-fill" style="font-size: 1.5rem; color: #8b5cf6;"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-info">
                                <i class="bi bi-calendar-check"></i> {{ $upcomingTrainings ?? 0 }} upcoming
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card shadow-sm" style="border-left: 4px solid #10b981;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Survey Responses</p>
                                <h2 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #10b981;">
                                    {{ $surveyResponses ?? 0 }}
                                </h2>
                            </div>
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-clipboard-data-fill" style="font-size: 1.5rem; color: #10b981;"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i> {{ $surveyResponseData['percentage'] ?? 0 }}% response rate
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card shadow-sm" style="border-left: 4px solid #f59e0b;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Attendance Rate</p>
                                <h2 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #f59e0b;">
                                    {{ $attendanceData['percentage'] ?? 0 }}%
                                </h2>
                            </div>
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-graph-up" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> This year
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Monthly Training Trend -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-graph-up text-primary me-2"></i>Training Trend (Last 6 Months)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="trainingTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Distribution -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-pie-chart text-success me-2"></i>Attendance Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights Row - Top Department & Recommended Training -->
        <div class="row g-4 mb-4">
            <!-- Department with Highest Training Count -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-trophy me-2"></i>Top Training Department
                        </h5>
                    </div>
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-building" style="font-size: 3rem; color: #6366f1; opacity: 0.3;"></i>
                        </div>
                        <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #6366f1;">
                            {{ $topTrainingDepartment['name'] }}
                        </h3>
                        <p class="text-muted mb-3">Department with most trainings this year</p>
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="mb-0 text-primary">{{ $topTrainingDepartment['count'] }}</h4>
                                <small class="text-muted">Trainings</small>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-0 text-success">{{ $topTrainingDepartment['percentage'] }}%</h4>
                                <small class="text-muted">Of Total</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-gradient" style="width: {{ $topTrainingDepartment['percentage'] }}%; background: linear-gradient(90deg, #6366f1, #4f46e5);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Recommended Training from TNA -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-star me-2"></i>Most Requested Training (TNA)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-lightbulb text-white" style="font-size: 1.8rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2" style="font-weight: 600;">{{ $topRecommendedTraining['title'] }}</h5>
                                @if($topRecommendedTraining['topic'])
                                    <p class="text-muted small mb-3">{{ Str::limit($topRecommendedTraining['description'] ?? 'No description available', 100) }}</p>
                                @endif
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-people me-1"></i>{{ $topRecommendedTraining['request_count'] }} requests
                                    </span>
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        <i class="bi bi-graph-up me-1"></i>{{ $topRecommendedTraining['percentage'] }}% popularity
                                    </span>
                                </div>
                                @if($topRecommendedTraining['topic'])
                                    <div class="mt-3">
                                        <a href="{{ route('training-topics.show', $topRecommendedTraining['topic']) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye me-1"></i>View Topic Details
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department & Activity Row -->
        <div class="row g-4 mb-4">
            <!-- Department Distribution -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-building text-info me-2"></i>Employees by Department
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-clock-history text-warning me-2"></i>Recent Activity
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            <div class="activity-timeline">
                                @foreach($recentActivities as $activity)
                                    <div class="activity-item">
                                        <div class="activity-icon" style="border-color: 
                                            @if($activity['color'] == 'primary') #3b82f6
                                            @elseif($activity['color'] == 'success') #10b981
                                            @elseif($activity['color'] == 'warning') #f59e0b
                                            @elseif($activity['color'] == 'danger') #ef4444
                                            @else #64748b @endif;">
                                            <i class="{{ $activity['icon'] }}" style="color: 
                                                @if($activity['color'] == 'primary') #3b82f6
                                                @elseif($activity['color'] == 'success') #10b981
                                                @elseif($activity['color'] == 'warning') #f59e0b
                                                @elseif($activity['color'] == 'danger') #ef4444
                                                @else #64748b @endif;"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $activity['title'] }}</strong>
                                            <p class="text-muted small mb-0">{{ $activity['description'] }}</p>
                                            <small class="text-muted">{{ $activity['time'] }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Trainings & Quick Actions Row -->
        <div class="row g-4">
            <!-- Upcoming Trainings -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-calendar-event text-primary me-2"></i>Upcoming Trainings
                        </h5>
                        <a href="{{ route('trainings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @if(isset($upcomingTrainingsList) && count($upcomingTrainingsList) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($upcomingTrainingsList as $training)
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="date-badge" style="background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; padding: 0.5rem; border-radius: 10px; text-align: center; min-width: 60px;">
                                                    <div style="font-size: 1.25rem; font-weight: 700; line-height: 1;">{{ date('d', strtotime($training->start_date)) }}</div>
                                                    <div style="font-size: 0.75rem; text-transform: uppercase;">{{ date('M', strtotime($training->start_date)) }}</div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" style="font-weight: 600;">{{ $training->title }}</h6>
                                                <p class="text-muted small mb-2">{{ $training->venue ?? 'TBA' }}</p>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <span class="badge bg-primary-subtle text-primary">{{ ucfirst($training->type) }}</span>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="bi bi-people me-1"></i>{{ $training->attendances->count() }} attendees
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3">No upcoming trainings scheduled</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('employees.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-person-plus me-2"></i>Add New Employee
                            </a>
                            <a href="{{ route('trainings.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-plus-circle me-2"></i>Create Training
                            </a>
                            <a href="{{ route('hr-documents.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-file-earmark-plus me-2"></i>Upload HR Document
                            </a>
                            <a href="{{ route('surveys.index') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-clipboard-data me-2"></i>View Survey Results
                            </a>
                            <a href="{{ route('departments.index') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-building me-2"></i>Manage Departments
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Survey Response Card -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-clipboard-check text-success" style="font-size: 2.5rem;"></i>
                        </div>
                        <h6 class="mb-1">Survey Response Rate</h6>
                        <h3 class="mb-2 text-success">{{ $surveyResponseData['percentage'] ?? 0 }}%</h3>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $surveyResponseData['percentage'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">
                            {{ $surveyResponseData['data'][0] ?? 0 }} of {{ ($surveyResponseData['data'][0] ?? 0) + ($surveyResponseData['data'][1] ?? 0) }} employees responded
                        </p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Employee Dashboard -->
        
        {{-- Training Attendance Reminders --}}
        @if(isset($upcomingTrainingAttendance) && $upcomingTrainingAttendance->count() > 0)
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="alert-heading mb-2">
                            <i class="bi bi-calendar-event me-2"></i>Training Attendance Required!
                        </h5>
                        <p class="mb-2">You are registered for the following upcoming training(s). Please mark your calendar and prepare to attend:</p>
                        <ul class="mb-2">
                            @foreach($upcomingTrainingAttendance as $attendance)
                                <li class="mb-2">
                                    <strong>{{ $attendance->training->title }}</strong>
                                    <br>
                                    <small>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->training->start_date)->format('M d, Y') }}
                                        @if($attendance->training->start_date === now()->toDateString())
                                            <span class="badge bg-white text-danger ms-2">TODAY!</span>
                                        @elseif(\Carbon\Carbon::parse($attendance->training->start_date)->diffInDays(now()) <= 3)
                                            <span class="badge bg-white text-warning ms-2">
                                                In {{ \Carbon\Carbon::parse($attendance->training->start_date)->diffInDays(now()) }} days
                                            </span>
                                        @endif
                                        • <i class="bi bi-geo-alt me-1"></i>{{ $attendance->training->venue }}
                                        • <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->training->start_time)->format('g:i A') }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('my-trainings') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-journal-bookmark me-2"></i>View All My Trainings
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-check text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ $myTrainingsCount ?? 0 }}</h3>
                        <p class="text-muted mb-0">Trainings Attended</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-folder text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ $myFilesCount ?? 0 }}</h3>
                        <p class="text-muted mb-0">201 Files</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-envelope text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ $myUnreadMessages ?? 0 }}</h3>
                        <p class="text-muted mb-0">Unread Messages</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-bell text-danger" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ $unreadNotifications ?? 0 }}</h3>
                        <p class="text-muted mb-0">Notifications</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('my-profile') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-person me-2"></i>My Profile
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('my-trainings') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-journal-bookmark me-2"></i>My Trainings
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('my-files') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-folder2-open me-2"></i>My 201 Files
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('training-survey.form') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-clipboard-data me-2"></i>Training Survey
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@if(auth()->user()->isStaff())
<script>
// Chart.js Configuration
Chart.defaults.font.family = "'Work Sans', sans-serif";
Chart.defaults.color = '#64748b';

// Training Trend Chart (Line)
const trainingTrendCtx = document.getElementById('trainingTrendChart');
if (trainingTrendCtx) {
    new Chart(trainingTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrainingData['labels'] ?? []) !!},
            datasets: [{
                label: 'Trainings Created',
                data: {!! json_encode($monthlyTrainingData['data'] ?? []) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Attendance Chart (Doughnut)
const attendanceCtx = document.getElementById('attendanceChart');
if (attendanceCtx) {
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($attendanceData['labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($attendanceData['data'] ?? []) !!},
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12
                }
            },
            cutout: '65%'
        }
    });
}

// Department Chart (Bar)
const departmentCtx = document.getElementById('departmentChart');
if (departmentCtx) {
    new Chart(departmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($departmentChartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Employees',
                data: {!! json_encode($departmentChartData['data'] ?? []) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: '#3b82f6',
                borderWidth: 1,
                borderRadius: 8,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
}
</script>
@endif
@endpush
