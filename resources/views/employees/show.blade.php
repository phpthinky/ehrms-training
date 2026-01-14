@extends('layouts.app')

@section('title', 'Employee Details - EHRMS')
@section('page-title', 'Employee Profile')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">{{ $employee->getFullNameAttribute() }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Profile Header -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body text-center p-4">
                    <div class="avatar mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 20px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 2rem;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                    
                    <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">
                        {{ $employee->getFullNameAttribute() }}
                    </h4>
                    <p class="text-muted mb-2">{{ $employee->position }}</p>
                    <p class="mb-3">
                        <span class="badge bg-primary-subtle text-primary">{{ $employee->employee_number }}</span>
                    </p>

                    @if($employee->status === 'active')
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Active Employee
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i>Inactive
                        </span>
                    @endif

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">Quick Stats</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Trainings Attended</span>
                        <strong>{{ $employee->trainings->where('attendance_status', 'attended')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">201 Files</span>
                        <strong>{{ $employee->files->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Years of Service</span>
                        <strong>{{ $employee->date_hired ? now()->diffInYears($employee->date_hired) : 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info" type="button">
                                <i class="bi bi-person me-2"></i>Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#trainings" type="button">
                                <i class="bi bi-journal-bookmark me-2"></i>Trainings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#files" type="button">
                                <i class="bi bi-file-earmark-text me-2"></i>201 Files
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <h6 class="mb-3 text-primary" style="font-weight: 600;">Personal Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Full Name</small>
                                    <strong>{{ $employee->getFullNameAttribute() }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Email Address</small>
                                    <strong>{{ $employee->email ?? $employee->user->email ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Mobile Number</small>
                                    <strong>{{ $employee->mobile_number ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Birth Date</small>
                                    <strong>{{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('F d, Y') : 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Gender</small>
                                    <strong>{{ ucfirst($employee->gender ?? 'N/A') }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Civil Status</small>
                                    <strong>{{ ucfirst($employee->civil_status ?? 'N/A') }}</strong>
                                </div>
                            </div>

                            <h6 class="mb-3 text-primary" style="font-weight: 600;">Employment Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Employee Number</small>
                                    <strong>{{ $employee->employee_number }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Department</small>
                                    <strong>{{ $employee->department->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Position</small>
                                    <strong>{{ $employee->position }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Rank Level</small>
                                    <strong>{{ ucfirst($employee->rank_level) }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Employment Type</small>
                                    @if($employee->employment_type === 'permanent')
                                        <span class="badge bg-success">Permanent</span>
                                    @elseif($employee->employment_type === 'job_order')
                                        <span class="badge bg-warning">Job Order</span>
                                    @else
                                        <span class="badge bg-info">Contract</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Date Hired</small>
                                    <strong>{{ $employee->date_hired ? \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Trainings Tab -->
                        <div class="tab-pane fade" id="trainings" role="tabpanel">
                            <h6 class="mb-3" style="font-weight: 600;">Training History</h6>
                            @if($employee->trainings->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($employee->trainings as $attendance)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $attendance->training->title }}</h6>
                                                    <p class="mb-1 text-muted small">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        {{ \Carbon\Carbon::parse($attendance->training->start_date)->format('M d, Y') }}
                                                    </p>
                                                    <div class="mt-2">
                                                        @if($attendance->attendance_status === 'attended')
                                                            <span class="badge bg-success-subtle text-success">Attended</span>
                                                        @else
                                                            <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($attendance->attendance_status) }}</span>
                                                        @endif
                                                        
                                                        @if($attendance->certificate_uploaded)
                                                            <span class="badge bg-primary-subtle text-primary">
                                                                <i class="bi bi-award me-1"></i>Certificate Uploaded
                                                            </span>
                                                        @endif
                                                    </div>
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

                        <!-- Files Tab -->
                        <div class="tab-pane fade" id="files" role="tabpanel">
                            <h6 class="mb-3" style="font-weight: 600;">201 Files</h6>
                            @if($employee->files->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($employee->files as $file)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-text text-primary me-3" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <h6 class="mb-0">{{ $file->file_name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $file->file_type }} • {{ $file->getFileSizeFormattedAttribute() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3">No files uploaded yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
