@extends('layouts.app')

@section('title', 'Training History - EHRMS')
@section('page-title', 'Employee Training History')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->getFullNameAttribute() }}</a></li>
            <li class="breadcrumb-item active">Trainings Attended</li>
        </ol>
    </nav>

    <!-- Employee Header -->
    <div class="card border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-center">
                    <!-- Avatar -->
                    <div class="avatar me-3" style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.2rem;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-weight: 600;">{{ $employee->getFullNameAttribute() }}</h5>
                        <div class="text-muted small">
                            <i class="bi bi-person-badge me-1"></i>{{ $employee->employee_number }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-briefcase me-1"></i>{{ $employee->position }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-building me-1"></i>{{ $employee->department->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Training List -->
        <div class="col-lg-9">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-journal-bookmark text-primary me-2"></i>
                        Trainings Attended
                        <span class="badge bg-primary-subtle text-primary ms-2">
                            {{ $trainings->where('attendance_status', 'attended')->count() }}
                        </span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($trainings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($trainings as $attendance)
                                <div class="list-group-item">
                                    <div class="row align-items-start">
                                        <!-- Training Icon/Badge -->
                                        <div class="col-auto">
                                            <div class="d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 10px; background: {{ $attendance->attendance_status === 'attended' ? '#dcfce7' : '#f3f4f6' }};">
                                                <i class="bi bi-mortarboard-fill" style="font-size: 1.5rem; color: {{ $attendance->attendance_status === 'attended' ? '#16a34a' : '#9ca3af' }};"></i>
                                            </div>
                                        </div>

                                        <!-- Training Details -->
                                        <div class="col">
                                            <h6 class="mb-2" style="font-weight: 600;">{{ $attendance->training->title }}</h6>

                                            <div class="row g-2 mb-2 small text-muted">
                                                <div class="col-md-6">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($attendance->training->start_date)->format('M d, Y') }}
                                                    @if($attendance->training->end_date && $attendance->training->end_date != $attendance->training->start_date)
                                                        - {{ \Carbon\Carbon::parse($attendance->training->end_date)->format('M d, Y') }}
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $attendance->training->venue ?? 'N/A' }}
                                                </div>
                                                @if($attendance->training->topic)
                                                    <div class="col-md-6">
                                                        <i class="bi bi-tag me-1"></i>
                                                        {{ $attendance->training->topic->name }}
                                                    </div>
                                                @endif
                                                <div class="col-md-6">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $attendance->training->hours ?? 0 }} hours
                                                </div>
                                            </div>

                                            <!-- Status Badges -->
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($attendance->attendance_status === 'attended')
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-check-circle me-1"></i>Attended
                                                    </span>
                                                @elseif($attendance->attendance_status === 'absent')
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Absent
                                                    </span>
                                                @elseif($attendance->attendance_status === 'registered')
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="bi bi-hourglass-split me-1"></i>Registered
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ ucfirst($attendance->attendance_status) }}
                                                    </span>
                                                @endif

                                                @if($attendance->certificate_uploaded)
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="bi bi-award me-1"></i>Certificate Uploaded
                                                    </span>
                                                @endif

                                                @if($attendance->training->training_type)
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ ucwords(str_replace('_', ' ', $attendance->training->training_type)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Additional Notes -->
                                            @if($attendance->notes)
                                                <div class="mt-2 p-2 bg-light rounded small">
                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                    <strong>Notes:</strong> {{ $attendance->notes }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Date Added -->
                                        <div class="col-auto text-end">
                                            <small class="text-muted">
                                                Registered:<br>
                                                {{ $attendance->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3">No training records yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Statistics -->
        <div class="col-lg-3">
            <!-- Training Statistics -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>Training Statistics
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Trainings</span>
                        <strong>{{ $trainings->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Attended</span>
                        <strong class="text-success">{{ $trainings->where('attendance_status', 'attended')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Absent</span>
                        <strong class="text-danger">{{ $trainings->where('attendance_status', 'absent')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Registered</span>
                        <strong class="text-warning">{{ $trainings->where('attendance_status', 'registered')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Certificates</span>
                        <strong class="text-primary">{{ $trainings->where('certificate_uploaded', true)->count() }}</strong>
                    </div>

                    @if($trainings->where('attendance_status', 'attended')->count() > 0)
                        <hr class="my-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total Hours</span>
                            <strong class="text-info">
                                {{ $trainings->where('attendance_status', 'attended')->sum(function($attendance) {
                                    return $attendance->training->hours ?? 0;
                                }) }} hrs
                            </strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person me-2"></i>View Profile
                        </a>
                        <a href="{{ route('employee-files.index', $employee) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-folder me-2"></i>View 201 Files
                        </a>
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Employees
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attendance Rate -->
            @if($trainings->count() > 0)
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-percent text-primary me-2"></i>Attendance Rate
                        </h6>
                        @php
                            $attendanceRate = ($trainings->where('attendance_status', 'attended')->count() / $trainings->count()) * 100;
                        @endphp
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                        <p class="text-center mb-0">
                            <strong>{{ round($attendanceRate) }}%</strong>
                            <small class="text-muted d-block">of trainings attended</small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
