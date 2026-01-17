@extends('layouts.app')

@section('title', 'My Profile - EHRMS')
@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    @if(!$employee)
        <!-- No Employee Record -->
        <div class="card border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-person-x text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                <h5 class="mt-3 mb-2">No Employee Record Found</h5>
                <p class="text-muted mb-4">Your account is not linked to an employee record. Please contact HR.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    @else
        <!-- Profile Header -->
        <div class="card border-0 mb-4">
            <div class="card-body p-4" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05));">
                <div class="row align-items-center">
                    <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                        <!-- Large Avatar -->
                        <div class="avatar mx-auto" style="width: 120px; height: 120px; border-radius: 20px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 3rem; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col-md">
                        <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            {{ $employee->getFullNameAttribute() }}
                        </h3>
                        <p class="text-muted mb-3">{{ $employee->position }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="bi bi-person-badge me-1"></i>{{ $employee->employee_number }}
                            </span>
                            <span class="badge bg-info px-3 py-2">
                                <i class="bi bi-building me-1"></i>{{ $employee->department->name ?? 'N/A' }}
                            </span>
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>{{ ucfirst($employee->status) }}
                            </span>
                            <span class="badge bg-warning px-3 py-2">
                                <i class="bi bi-star me-1"></i>{{ ucfirst($employee->rank_level ?? 'Regular') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0" style="font-weight: 600;">
                            <i class="bi bi-person text-primary me-2"></i>Personal Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">First Name</label>
                                <div class="fw-semibold">{{ $employee->first_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Last Name</label>
                                <div class="fw-semibold">{{ $employee->last_name }}</div>
                            </div>
                            @if($employee->middle_name)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Middle Name</label>
                                <div class="fw-semibold">{{ $employee->middle_name }}</div>
                            </div>
                            @endif
                            @if($employee->email)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Email Address</label>
                                <div class="fw-semibold">
                                    <i class="bi bi-envelope text-primary me-2"></i>{{ $employee->email }}
                                </div>
                            </div>
                            @endif
                            @if($employee->contact_number)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Contact Number</label>
                                <div class="fw-semibold">
                                    <i class="bi bi-phone text-success me-2"></i>{{ $employee->contact_number }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0" style="font-weight: 600;">
                            <i class="bi bi-briefcase text-success me-2"></i>Employment Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Employee Number</label>
                                <div class="fw-semibold">{{ $employee->employee_number }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Position</label>
                                <div class="fw-semibold">{{ $employee->position }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Department</label>
                                <div class="fw-semibold">{{ $employee->department->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Rank Level</label>
                                <div class="fw-semibold">{{ ucfirst($employee->rank_level ?? 'Regular') }}</div>
                            </div>
                            @if($employee->hire_date)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Date Hired</label>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($employee->hire_date)->format('F d, Y') }}</div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Employment Status</label>
                                <div>
                                    <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $employee->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Training History -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0" style="font-weight: 600;">
                                <i class="bi bi-mortarboard text-warning me-2"></i>My Training History
                            </h6>
                            <span class="badge bg-primary">{{ $employee->trainings->count() }} Trainings</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($employee->trainings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Training Title</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->trainings->take(5) as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $attendance->training->title ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $attendance->training->topic->name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $attendance->training->start_date ? \Carbon\Carbon::parse($attendance->training->start_date)->format('M d, Y') : 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'attended' => 'success',
                                                            'absent' => 'danger',
                                                            'pending' => 'warning'
                                                        ];
                                                        $color = $statusColors[$attendance->attendance_status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                                        {{ ucfirst($attendance->attendance_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($attendance->certificate_uploaded)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="bi bi-award me-1"></i>Available
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($employee->trainings->count() > 5)
                                <div class="card-footer bg-light text-center">
                                    <a href="{{ route('my-trainings') }}" class="btn btn-sm btn-outline-primary">
                                        View All Trainings
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-mortarboard text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3 mb-0">No training records yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card border-0 mb-3">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-bar-chart text-primary me-2"></i>Quick Stats
                        </h6>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">Total Trainings</span>
                            <strong>{{ $employee->trainings->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">Attended</span>
                            <strong class="text-success">{{ $employee->trainings->where('attendance_status', 'attended')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">Certificates</span>
                            <strong class="text-warning">{{ $employee->trainings->where('certificate_uploaded', true)->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">201 Files</span>
                            <strong class="text-info">{{ $employee->files->count() }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 mb-3">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-lightning text-warning me-2"></i>Quick Actions
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('my-trainings') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-mortarboard me-2"></i>View My Trainings
                            </a>
                            <a href="{{ route('employee-files.index', $employee) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-files me-2"></i>View My 201 Files
                            </a>
                            <a href="{{ route('training-survey.form') }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-clipboard-data me-2"></i>Training Survey
                            </a>
                            <a href="{{ route('messages.create') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-envelope me-2"></i>Message HR
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-person-circle text-secondary me-2"></i>Account Information
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Username</span>
                            <strong class="small">{{ auth()->user()->username }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Email</span>
                            <strong class="small">{{ auth()->user()->email }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Role</span>
                            <strong class="small">{{ ucfirst(auth()->user()->role) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
