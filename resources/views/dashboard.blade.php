@extends('layouts.app')

@section('title', 'Dashboard - EHRMS')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);">
                <div class="card-body p-4 text-white">
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
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card" style="border-left: 4px solid #3b82f6;">
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
                <div class="card border-0 stat-card" style="border-left: 4px solid #8b5cf6;">
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
                <div class="card border-0 stat-card" style="border-left: 4px solid #10b981;">
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
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> For {{ date('Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 stat-card" style="border-left: 4px solid #f59e0b;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Pending Requests</p>
                                <h2 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: #f59e0b;">
                                    {{ $pendingRequests ?? 0 }}
                                </h2>
                            </div>
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-hourglass-split" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-warning">
                                <i class="bi bi-exclamation-circle"></i> Needs attention
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row g-4">
            <!-- Upcoming Trainings -->
            <div class="col-lg-8">
                <div class="card border-0">
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
                <div class="card border-0">
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
                            <a href="{{ route('public-files.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-upload me-2"></i>Upload Public File
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-file-bar-graph me-2"></i>Generate Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Departments Summary -->
                <div class="card border-0 mt-3">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-diagram-3 text-info me-2"></i>Department Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($departmentStats) && count($departmentStats) > 0)
                            @foreach($departmentStats as $dept)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">{{ $dept->name }}</h6>
                                        <small class="text-muted">{{ $dept->code }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $dept->employees_count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No departments found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Employee Dashboard -->
        
        <!-- Employee Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 h-100" style="border-left: 4px solid #3b82f6;">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-check" style="font-size: 2.5rem; color: #3b82f6;"></i>
                        <h3 class="mt-3 mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">{{ $myTrainingsCount ?? 0 }}</h3>
                        <p class="text-muted mb-0">Trainings Attended</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 h-100" style="border-left: 4px solid #10b981;">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-check" style="font-size: 2.5rem; color: #10b981;"></i>
                        <h3 class="mt-3 mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">{{ $myFilesCount ?? 0 }}</h3>
                        <p class="text-muted mb-0">201 Files</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 h-100" style="border-left: 4px solid #f59e0b;">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history" style="font-size: 2.5rem; color: #f59e0b;"></i>
                        <h3 class="mt-3 mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">{{ $myPendingRequests ?? 0 }}</h3>
                        <p class="text-muted mb-0">Pending Requests</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 h-100" style="border-left: 4px solid #8b5cf6;">
                    <div class="card-body text-center">
                        <i class="bi bi-envelope" style="font-size: 2.5rem; color: #8b5cf6;"></i>
                        <h3 class="mt-3 mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">{{ $myUnreadMessages ?? 0 }}</h3>
                        <p class="text-muted mb-0">Unread Messages</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- My Upcoming Trainings -->
            <div class="col-lg-8">
                <div class="card border-0">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-calendar-event text-primary me-2"></i>My Upcoming Trainings
                        </h5>
                        <a href="{{ route('my-trainings') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-check" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3">No upcoming trainings</p>
                            <small>Check back later for new training opportunities</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-4">
                <div class="card border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-link-45deg text-primary me-2"></i>Quick Links
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('my-profile') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-person me-2"></i>View My Profile
                            </a>
                            <a href="{{ route('my-files') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-folder me-2"></i>My 201 Files
                            </a>
                            <a href="{{ route('training-survey') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-clipboard-check me-2"></i>Training Survey
                            </a>
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-envelope me-2"></i>My Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection
