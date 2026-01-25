@extends('layouts.app')

@section('title', $trainingTopic->title . ' - EHRMS')
@section('page-title', 'Training Topic Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-topics.index') }}">Training Topics</a></li>
            <li class="breadcrumb-item active">{{ $trainingTopic->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Topic Details -->
        <div class="col-lg-8">
            <!-- Header Card -->
            <div class="card border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <h4 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 700;">
                                {{ $trainingTopic->title }}
                            </h4>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge
                                    @if($trainingTopic->category === 'technical') bg-primary-subtle text-primary
                                    @elseif($trainingTopic->category === 'leadership') bg-warning-subtle text-warning
                                    @elseif($trainingTopic->category === 'compliance') bg-danger-subtle text-danger
                                    @elseif($trainingTopic->category === 'soft_skills') bg-success-subtle text-success
                                    @else bg-secondary-subtle text-secondary
                                    @endif
                                    ">
                                    <i class="bi
                                        @if($trainingTopic->category === 'technical') bi-cpu
                                        @elseif($trainingTopic->category === 'leadership') bi-award
                                        @elseif($trainingTopic->category === 'compliance') bi-shield-check
                                        @elseif($trainingTopic->category === 'soft_skills') bi-people
                                        @else bi-bookmark
                                        @endif
                                        me-1"></i>
                                    {{ ucwords(str_replace('_', ' ', $trainingTopic->category)) }}
                                </span>

                                @if($trainingTopic->rank_level === 'all')
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="bi bi-people me-1"></i>All Employees
                                    </span>
                                @elseif($trainingTopic->rank_level === 'higher')
                                    <span class="badge bg-purple-subtle text-purple">
                                        <i class="bi bi-star me-1"></i>Higher Rank
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-person me-1"></i>Normal Rank
                                    </span>
                                @endif

                                @if($trainingTopic->is_active)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </div>

                            @if($trainingTopic->description)
                                <p class="text-muted mb-0">{{ $trainingTopic->description }}</p>
                            @else
                                <p class="text-muted fst-italic mb-0">No description provided</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('training-topics.edit', $trainingTopic) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Topic
                        </a>
                        <a href="{{ route('training-topics.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Trainings -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-journal-text text-primary me-2"></i>
                        Trainings Under This Topic
                        <span class="badge bg-primary-subtle text-primary ms-2">{{ $trainingTopic->trainings_count }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($trainingTopic->trainings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($trainingTopic->trainings as $training)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('trainings.show', $training) }}" class="text-decoration-none">
                                                    {{ $training->title }}
                                                </a>
                                            </h6>
                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ \Carbon\Carbon::parse($training->start_date)->format('M d, Y') }}
                                                @if($training->venue)
                                                    <span class="mx-2">•</span>
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $training->venue }}
                                                @endif
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($training->type === 'internal')
                                                    <span class="badge bg-primary-subtle text-primary">Internal</span>
                                                @else
                                                    <span class="badge bg-info-subtle text-info">External</span>
                                                @endif

                                                @if($training->status === 'scheduled')
                                                    <span class="badge bg-warning-subtle text-warning">Scheduled</span>
                                                @elseif($training->status === 'completed')
                                                    <span class="badge bg-success-subtle text-success">Completed</span>
                                                @elseif($training->status === 'cancelled')
                                                    <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                                                @endif

                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-people me-1"></i>{{ $training->attendances()->count() }} attendees
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($trainingTopic->trainings_count > 10)
                            <div class="card-footer bg-white border-top text-center">
                                <a href="{{ route('trainings.index') }}?topic={{ $trainingTopic->id }}" class="btn btn-sm btn-outline-primary">
                                    View All {{ $trainingTopic->trainings_count }} Trainings
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3">No trainings under this topic yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="bi bi-bar-chart text-primary me-2"></i>Statistics
                    </h6>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Trainings</span>
                            <strong class="text-primary">{{ $trainingTopic->trainings_count }}</strong>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Completed</span>
                            <strong class="text-success">{{ $trainingTopic->trainings()->where('status', 'completed')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Scheduled</span>
                            <strong class="text-warning">{{ $trainingTopic->trainings()->where('status', 'scheduled')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Ongoing</span>
                            <strong class="text-info">{{ $trainingTopic->trainings()->where('status', 'ongoing')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Cancelled</span>
                            <strong class="text-danger">{{ $trainingTopic->trainings()->where('status', 'cancelled')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topic Info -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>Topic Information
                    </h6>

                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Category</small>
                        <strong>{{ ucwords(str_replace('_', ' ', $trainingTopic->category)) }}</strong>
                    </div>

                    <div class="mb-2 mt-3">
                        <small class="text-muted d-block mb-1">Rank Level</small>
                        <strong>
                            @if($trainingTopic->rank_level === 'all')
                                All Employees
                            @elseif($trainingTopic->rank_level === 'higher')
                                Higher Rank Only
                            @else
                                Normal Rank Only
                            @endif
                        </strong>
                    </div>

                    <div class="mb-2 mt-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <strong>
                            @if($trainingTopic->is_active)
                                <span class="text-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="text-secondary"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                            @endif
                        </strong>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted d-block mb-1">Created</small>
                        <strong>{{ $trainingTopic->created_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
