@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $surveyTemplate->title }}</h1>
            <p class="text-muted">Year: {{ $surveyTemplate->year }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('survey-builder.index', $surveyTemplate) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-2"></i>Build Survey
            </a>
            <a href="{{ route('survey-responses.analytics', $surveyTemplate) }}" class="btn btn-info">
                <i class="bi bi-bar-chart me-2"></i>Analytics
            </a>
            <a href="{{ route('survey-templates.edit', $surveyTemplate) }}" class="btn btn-warning">
                <i class="bi bi-gear me-2"></i>Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Questions</h6>
                            <h2 class="mb-0">{{ $stats['total_questions'] }}</h2>
                        </div>
                        <i class="bi bi-question-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Responses</h6>
                            <h2 class="mb-0">{{ $stats['total_responses'] }}</h2>
                        </div>
                        <i class="bi bi-chat-square-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Response Rate</h6>
                            <h2 class="mb-0">{{ $stats['response_rate'] }}%</h2>
                        </div>
                        <i class="bi bi-pie-chart fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-{{ $surveyTemplate->is_active ? 'success' : 'secondary' }} text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Status</h6>
                            <h2 class="mb-0">{{ $surveyTemplate->is_active ? 'Active' : 'Inactive' }}</h2>
                        </div>
                        <i class="bi bi-{{ $surveyTemplate->is_active ? 'check' : 'pause' }}-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($surveyTemplate->description)
    <div class="card mb-4">
        <div class="card-body">
            <h5>Description</h5>
            <p class="mb-0">{{ $surveyTemplate->description }}</p>
        </div>
    </div>
    @endif

    <!-- Survey Questions -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Survey Questions ({{ $surveyTemplate->questions->count() }})</h5>
            <a href="{{ route('survey-builder.index', $surveyTemplate) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add Questions
            </a>
        </div>
        <div class="card-body">
            @if($surveyTemplate->questions->count() > 0)
                <div class="list-group">
                    @foreach($surveyTemplate->questions as $question)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-secondary">Q{{ $loop->iteration }}</span>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                        @if($question->pivot->is_required)
                                            <span class="badge bg-danger">Required</span>
                                        @endif
                                    </div>
                                    <p class="mb-1"><strong>{{ $question->question_text }}</strong></p>
                                    @if($question->help_text)
                                        <p class="text-muted small mb-0"><i class="bi bi-info-circle"></i> {{ $question->help_text }}</p>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-muted small">Order: {{ $question->pivot->order }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted">No questions added yet.</p>
                    <a href="{{ route('survey-builder.index', $surveyTemplate) }}" class="btn btn-primary">
                        Add Questions Now
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Responses -->
    @if($surveyTemplate->submittedResponses->count() > 0)
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Responses ({{ $surveyTemplate->submittedResponses->count() }})</h5>
            <a href="{{ route('survey-responses.index', $surveyTemplate) }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surveyTemplate->submittedResponses->take(10) as $response)
                            <tr>
                                <td>{{ $response->employee->first_name }} {{ $response->employee->last_name }}</td>
                                <td>{{ $response->employee->department->name ?? 'N/A' }}</td>
                                <td>{{ $response->submitted_at ? $response->submitted_at->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('survey-responses.show', $response) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
