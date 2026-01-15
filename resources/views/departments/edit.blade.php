@extends('layouts.app')

@section('title', 'Edit Department - EHRMS')
@section('page-title', 'Edit Department')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
            <li class="breadcrumb-item active">Edit {{ $department->name }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-pencil text-primary me-2"></i>Edit Department Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $department->name) }}" 
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Department Code <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="code" 
                                class="form-control @error('code') is-invalid @enderror" 
                                value="{{ old('code', $department->code) }}" 
                                required
                            >
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea 
                                name="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="4"
                            >{{ old('description', $department->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                                <option value="1" {{ old('is_active', $department->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $department->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Inactive departments won't appear in employee forms</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('departments.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Update Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Department Details
                    </h6>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Department ID</small>
                        <strong>#{{ $department->id }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Employees</small>
                        <strong>{{ $department->employees()->count() }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Created</small>
                        <strong>{{ $department->created_at->format('M d, Y') }}</strong>
                    </div>

                    @if($department->employees()->count() > 0)
                        <div class="alert alert-warning alert-sm mb-0">
                            <small><i class="bi bi-exclamation-triangle me-2"></i><strong>Note:</strong> This department has {{ $department->employees()->count() }} employee(s). Consider reassigning them before making it inactive.</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-clock-history text-primary me-2"></i>Activity
                    </h6>
                    <small class="text-muted d-block mb-2">
                        <strong>Created:</strong> {{ $department->created_at->diffForHumans() }}
                    </small>
                    <small class="text-muted d-block">
                        <strong>Last Updated:</strong> {{ $department->updated_at->diffForHumans() }}
                    </small>
                </div>
            </div>

            <!-- Employees in Department -->
            @if($department->employees()->count() > 0)
                <div class="card border-0 mt-3">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-people text-primary me-2"></i>Employees
                        </h6>
                        <div class="list-group list-group-flush">
                            @foreach($department->employees()->limit(5)->get() as $emp)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <small class="d-block fw-semibold">{{ $emp->getFullNameAttribute() }}</small>
                                    <small class="text-muted">{{ $emp->position }}</small>
                                </div>
                            @endforeach
                        </div>
                        @if($department->employees()->count() > 5)
                            <small class="text-muted">And {{ $department->employees()->count() - 5 }} more...</small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
