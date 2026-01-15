@extends('layouts.app')

@section('title', 'Add Department - EHRMS')
@section('page-title', 'Add Department')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
            <li class="breadcrumb-item active">Add Department</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-diagram-3 text-primary me-2"></i>Department Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="e.g., Human Resource Management Office"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Official department name</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Department Code <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="code" 
                                class="form-control @error('code') is-invalid @enderror" 
                                value="{{ old('code') }}" 
                                placeholder="e.g., HRMO"
                                required
                            >
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Short code or acronym (must be unique)</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea 
                                name="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="4"
                                placeholder="Brief description of the department's functions and responsibilities"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('departments.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Create Department
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
                        <i class="bi bi-info-circle text-primary me-2"></i>Department Guidelines
                    </h6>
                    
                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Department Name:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Use official/formal name</li>
                            <li>Must be unique</li>
                            <li>Example: "Human Resource Management Office"</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Department Code:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Short abbreviation</li>
                            <li>2-6 characters recommended</li>
                            <li>Must be unique</li>
                            <li>Examples: HRMO, MPDO, MENRO</li>
                        </ul>
                    </div>

                    <div class="alert alert-info alert-sm mb-0">
                        <small><i class="bi bi-lightbulb me-2"></i><strong>Tip:</strong> Department will be set to "Active" by default.</small>
                    </div>
                </div>
            </div>

            <!-- Current Departments -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-list-ul text-primary me-2"></i>Current Departments
                    </h6>
                    @php
                        $existingDepartments = \App\Models\Department::orderBy('name')->get();
                    @endphp
                    @if($existingDepartments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($existingDepartments->take(5) as $dept)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <small class="d-block fw-semibold">{{ $dept->name }}</small>
                                    <small class="text-muted">Code: {{ $dept->code }}</small>
                                </div>
                            @endforeach
                        </div>
                        @if($existingDepartments->count() > 5)
                            <small class="text-muted">And {{ $existingDepartments->count() - 5 }} more...</small>
                        @endif
                    @else
                        <p class="small text-muted mb-0">No departments yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
