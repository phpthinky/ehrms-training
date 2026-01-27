@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="h3">Annual Surveys</h1>
        <p class="text-muted">Complete the required surveys below. You can save your progress as a draft and continue later.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @foreach($surveysWithStatus as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 {{ $item['status'] === 'submitted' ? 'border-success' : ($item['status'] === 'draft' ? 'border-warning' : '') }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $item['template']->title }}</h5>
                        @if($item['status'] === 'submitted')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                        @elseif($item['status'] === 'draft')
                            <span class="badge bg-warning text-dark"><i class="bi bi-pencil me-1"></i>In Progress</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-circle me-1"></i>Not Started</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <i class="bi bi-calendar me-1"></i>Year: {{ $item['template']->year }}
                        </p>
                        @if($item['template']->description)
                            <p class="small mb-3">{{ Str::limit($item['template']->description, 100) }}</p>
                        @endif
                        <p class="text-muted small mb-0">
                            <i class="bi bi-question-circle me-1"></i>{{ $item['template']->questions()->count() }} questions
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        @if($item['status'] === 'submitted')
                            <div class="text-success small">
                                <i class="bi bi-check2-circle me-1"></i>
                                Submitted on {{ $item['submitted_at']->format('M d, Y H:i') }}
                            </div>
                        @elseif($item['status'] === 'draft')
                            <a href="{{ route('survey.form', ['template' => $item['template']->id]) }}" class="btn btn-warning w-100">
                                <i class="bi bi-pencil-square me-2"></i>Continue Survey
                            </a>
                        @else
                            <a href="{{ route('survey.form', ['template' => $item['template']->id]) }}" class="btn btn-primary w-100">
                                <i class="bi bi-play-circle me-2"></i>Start Survey
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($surveysWithStatus->every(fn($item) => $item['status'] === 'submitted'))
        <div class="alert alert-success mt-4">
            <h5><i class="bi bi-check-circle me-2"></i>All Surveys Completed!</h5>
            <p class="mb-0">Thank you for completing all the required surveys. Your feedback helps us improve our training programs.</p>
        </div>
    @endif
</div>
@endsection
