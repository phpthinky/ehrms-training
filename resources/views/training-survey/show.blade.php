@extends('layouts.app')

@section('title', 'Survey Details - EHRMS')
@section('page-title', 'Survey Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-surveys.index') }}">Training Surveys</a></li>
            <li class="breadcrumb-item active">{{ $survey->employee->getFullNameAttribute() }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Survey Response Card -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="bi bi-clipboard-check text-primary me-2"></i>Survey Response
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Selected Topics -->
                    <div class="mb-4">
                        <h6 class="mb-3" style="font-weight: 600;">Selected Training Topics</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($topics as $topic)
                                <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem;">
                                    <i class="bi bi-check2 me-1"></i>{{ $topic->title }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Additional Topics -->
                    @if($survey->other_topics)
                        <div class="mb-4">
                            <h6 class="mb-3" style="font-weight: 600;">Additional Suggested Topics</h6>
                            <div class="alert alert-light border mb-0">
                                <i class="bi bi-chat-left-quote text-primary me-2"></i>
                                {{ $survey->other_topics }}
                            </div>
                        </div>
                    @endif

                    <!-- Preferences -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3" style="font-weight: 600;">Preferred Schedule</h6>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar3 text-info me-2" style="font-size: 1.5rem;"></i>
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    {{ ucwords(str_replace('_', ' ', $survey->preferred_schedule ?? 'any')) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3" style="font-weight: 600;">Preferred Format</h6>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cast text-success me-2" style="font-size: 1.5rem;"></i>
                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                    {{ ucwords(str_replace('_', ' ', $survey->preferred_format ?? 'any')) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    @if($survey->remarks)
                        <div class="mb-0">
                            <h6 class="mb-3" style="font-weight: 600;">Additional Remarks</h6>
                            <div class="alert alert-light border mb-0">
                                <i class="bi bi-chat-left-text text-secondary me-2"></i>
                                {{ $survey->remarks }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Employee Info -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-person text-primary me-2"></i>Employee Information
                    </h6>
                    
                    <div class="text-center mb-3">
                        <div class="avatar mx-auto mb-2" style="width: 80px; height: 80px; border-radius: 16px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.8rem;">
                            {{ strtoupper(substr($survey->employee->first_name, 0, 1) . substr($survey->employee->last_name, 0, 1)) }}
                        </div>
                        <h6 class="mb-1">{{ $survey->employee->getFullNameAttribute() }}</h6>
                        <small class="text-muted">{{ $survey->employee->employee_number }}</small>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Position</span>
                        <strong class="small">{{ $survey->employee->position }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Department</span>
                        <strong class="small">{{ $survey->employee->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Rank Level</span>
                        <strong class="small">{{ ucfirst($survey->employee->rank_level ?? 'N/A') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Survey Info -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-info me-2"></i>Survey Information
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Survey Year</span>
                        <strong class="small">{{ $survey->year }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Status</span>
                        <span class="badge bg-success-subtle text-success">{{ ucfirst($survey->status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Submitted</span>
                        <strong class="small">{{ $survey->submitted_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Topics Selected</span>
                        <strong class="small">{{ $topics->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-warning me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('training-surveys.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Surveys
                        </a>
                        <a href="{{ route('employees.show', $survey->employee) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person me-2"></i>View Employee Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
