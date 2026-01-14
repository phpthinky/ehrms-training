@extends('layouts.app')

@section('title', 'Add Employee - EHRMS')
@section('page-title', 'Add New Employee')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">Add New Employee</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-person-plus text-primary me-2"></i>Employee Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <!-- Personal Information -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Personal Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Employment Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Employee Number <span class="text-danger">*</span></label>
                                <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror" value="{{ old('employee_number') }}" placeholder="e.g., EMP-HRMO-001" required>
                                @error('employee_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Must be unique</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="employee@sablayan.gov.ph" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Will be used for login</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" placeholder="e.g., Administrative Officer II" required>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="permanent" {{ old('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option value="job_order" {{ old('employment_type') == 'job_order' ? 'selected' : '' }}>Job Order</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                                @error('employment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Rank Level <span class="text-danger">*</span></label>
                                <select name="rank_level" class="form-select @error('rank_level') is-invalid @enderror" required>
                                    <option value="">Select Rank</option>
                                    <option value="normal" {{ old('rank_level') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="higher" {{ old('rank_level') == 'higher' ? 'selected' : '' }}>Higher</option>
                                </select>
                                @error('rank_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">For training eligibility</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date Hired</label>
                                <input type="date" name="date_hired" class="form-control @error('date_hired') is-invalid @enderror" value="{{ old('date_hired') }}">
                                @error('date_hired')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('employees.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Create Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Important Notes
                    </h6>
                    
                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Default Credentials:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Default password: <code>password</code></li>
                            <li>Employee should change password on first login</li>
                            <li>Email will be used as username</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Employee Number:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Format: EMP-DEPT-###</li>
                            <li>Example: EMP-HRMO-001</li>
                            <li>Must be unique</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Rank Level:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li><strong>Normal:</strong> Regular employees</li>
                            <li><strong>Higher:</strong> Managers, supervisors</li>
                            <li>Determines training eligibility</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning alert-sm mb-0">
                        <small><i class="bi bi-exclamation-triangle me-2"></i><strong>Note:</strong> Employee status will be set to "Active" by default.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
