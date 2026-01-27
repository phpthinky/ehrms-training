@extends('layouts.app')

@section('title', 'My Profile - EHRMS')
@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto"
                             style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        {{ $user->name }}
                    </h4>
                    <p class="text-muted mb-3">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>

                    @if($employee)
                        <div class="d-flex justify-content-center gap-4 mb-3">
                            <div class="text-center">
                                <h5 class="mb-0 text-primary">{{ $employee->trainings->count() }}</h5>
                                <small class="text-muted">Trainings</small>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-0 text-success">{{ $employee->trainings->where('attendance_status', 'attended')->count() }}</h5>
                                <small class="text-muted">Attended</small>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-0 text-info">{{ $employee->files->count() }}</h5>
                                <small class="text-muted">Files</small>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('settings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-gear me-2"></i>Account Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="col-lg-8">
            <!-- Account Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-person-circle text-primary me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Name</label>
                            <p class="mb-0 fw-semibold">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0 fw-semibold">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Role</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Account Created</label>
                            <p class="mb-0 fw-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($employee)
                <!-- Employee Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-person-badge text-primary me-2"></i>Employee Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small">Employee Number</label>
                                <p class="mb-0 fw-semibold">{{ $employee->employee_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <p class="mb-0 fw-semibold">{{ $employee->full_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Department</label>
                                <p class="mb-0 fw-semibold">{{ $employee->department->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Position</label>
                                <p class="mb-0 fw-semibold">{{ $employee->position ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Employment Type</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $employee->employment_type === 'permanent' ? 'success' : 'warning' }}">
                                        {{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? 'N/A')) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Rank Level</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $employee->rank_level === 'higher' ? 'info' : 'secondary' }}">
                                        {{ ucfirst($employee->rank_level ?? 'N/A') }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date Hired</label>
                                <p class="mb-0 fw-semibold">{{ $employee->date_hired?->format('F d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Years of Service</label>
                                <p class="mb-0 fw-semibold">{{ $employee->years_of_service_formatted }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($employee->status ?? 'N/A') }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Mobile Number</label>
                                <p class="mb-0 fw-semibold">{{ $employee->mobile_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="card border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-info-circle text-primary me-2"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small">Birth Date</label>
                                <p class="mb-0 fw-semibold">{{ $employee->birth_date?->format('F d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Gender</label>
                                <p class="mb-0 fw-semibold">{{ ucfirst($employee->gender ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Civil Status</label>
                                <p class="mb-0 fw-semibold">{{ ucfirst($employee->civil_status ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small">Address</label>
                                <p class="mb-0 fw-semibold">{{ $employee->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
