@extends('layouts.app')

@section('title', 'Edit Training Topic - EHRMS')
@section('page-title', 'Edit Training Topic')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-topics.index') }}">Training Topics</a></li>
            <li class="breadcrumb-item active">Edit: {{ $trainingTopic->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-pencil text-primary me-2"></i>Edit Training Topic
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('training-topics.update', $trainingTopic) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Topic Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $trainingTopic->title) }}"
                                   placeholder="e.g., Leadership and Management" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="3" placeholder="Brief description">{{ old('description', $trainingTopic->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                <option value="technical" {{ old('category', $trainingTopic->category) === 'technical' ? 'selected' : '' }}>
                                    Technical
                                </option>
                                <option value="soft_skills" {{ old('category', $trainingTopic->category) === 'soft_skills' ? 'selected' : '' }}>
                                    Soft Skills
                                </option>
                                <option value="compliance" {{ old('category', $trainingTopic->category) === 'compliance' ? 'selected' : '' }}>
                                    Compliance
                                </option>
                                <option value="leadership" {{ old('category', $trainingTopic->category) === 'leadership' ? 'selected' : '' }}>
                                    Leadership
                                </option>
                                <option value="other" {{ old('category', $trainingTopic->category) === 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rank Level -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Rank Level Eligibility <span class="text-danger">*</span></label>
                            <select name="rank_level" class="form-select @error('rank_level') is-invalid @enderror" required>
                                <option value="">Select Rank Level</option>
                                <option value="all" {{ old('rank_level', $trainingTopic->rank_level) === 'all' ? 'selected' : '' }}>
                                    All Employees
                                </option>
                                <option value="higher" {{ old('rank_level', $trainingTopic->rank_level) === 'higher' ? 'selected' : '' }}>
                                    Higher Rank Only
                                </option>
                                <option value="normal" {{ old('rank_level', $trainingTopic->rank_level) === 'normal' ? 'selected' : '' }}>
                                    Normal Rank Only
                                </option>
                            </select>
                            @error('rank_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       {{ old('is_active', $trainingTopic->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Active Topic
                                </label>
                            </div>
                            <small class="text-muted">Active topics can be selected when creating trainings</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Topic
                            </button>
                            <a href="{{ route('training-topics.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-bar-chart text-primary me-2"></i>Topic Statistics</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Trainings</span>
                        <strong>{{ $trainingTopic->trainings()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Completed</span>
                        <strong>{{ $trainingTopic->trainings()->where('status', 'completed')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Upcoming</span>
                        <strong>{{ $trainingTopic->trainings()->where('status', 'scheduled')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Status</span>
                        <strong>
                            @if($trainingTopic->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </strong>
                    </div>
                </div>
            </div>

            @if($trainingTopic->trainings()->count() > 0)
                <div class="alert alert-info border-0 mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        <strong>Note:</strong> This topic has {{ $trainingTopic->trainings()->count() }} training(s).
                        Changes will affect how this topic is displayed but won't modify existing trainings.
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
