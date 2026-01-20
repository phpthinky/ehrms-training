@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Training Recommendations (TNA)</h1>
            <p class="text-muted">Based on Training Needs Analysis survey responses</p>
        </div>
        <div>
            <a href="{{ route('trainings.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Trainings
            </a>
        </div>
    </div>

    <!-- Filter by Year -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('training-recommendations') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filter by Year</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 text-end">
                    <div class="d-flex gap-2 justify-content-end align-items-center">
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">Critical ≥70%</span>
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">High ≥50%</span>
                        <span class="badge bg-info-subtle text-info px-3 py-2">Medium ≥30%</span>
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Low <30%</span>
                        <span class="badge bg-success-subtle text-success px-3 py-2">Scheduled</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(count($recommendations) > 0)
        <!-- Summary Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-data text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ count($recommendations) }}</h3>
                        <p class="text-muted mb-0">Training Topics Requested</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ $recommendations[0]['total_responses'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Survey Responses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ collect($recommendations)->where('priority', 'critical')->count() }}</h3>
                        <p class="text-muted mb-0">Critical Priority</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2">{{ collect($recommendations)->where('has_scheduled_training', true)->count() }}</h3>
                        <p class="text-muted mb-0">Already Scheduled</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Recommended Training Programs</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 30%;">Training Topic</th>
                                <th style="width: 15%;">Rank Level</th>
                                <th style="width: 15%;">Requests</th>
                                <th style="width: 15%;">Demand</th>
                                <th style="width: 15%;">Priority</th>
                                <th style="width: 5%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recommendations as $index => $rec)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $rec['topic']->title }}</strong>
                                        @if($rec['topic']->description)
                                            <br><small class="text-muted">{{ Str::limit($rec['topic']->description, 80) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ ucfirst($rec['topic']->rank_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $rec['request_count'] }}</strong> / {{ $rec['total_responses'] }}
                                        <br><small class="text-muted">employees</small>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar
                                                @if($rec['percentage'] >= 70) bg-danger
                                                @elseif($rec['percentage'] >= 50) bg-warning
                                                @elseif($rec['percentage'] >= 30) bg-info
                                                @else bg-secondary
                                                @endif"
                                                role="progressbar"
                                                style="width: {{ $rec['percentage'] }}%;">
                                                <strong>{{ $rec['percentage'] }}%</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rec['priority'] === 'scheduled')
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Scheduled
                                            </span>
                                        @elseif($rec['priority'] === 'critical')
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Critical
                                            </span>
                                        @elseif($rec['priority'] === 'high')
                                            <span class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-arrow-up me-1"></i>High
                                            </span>
                                        @elseif($rec['priority'] === 'medium')
                                            <span class="badge bg-info px-3 py-2">
                                                <i class="bi bi-dash me-1"></i>Medium
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="bi bi-arrow-down me-1"></i>Low
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('training-topics.show', $rec['topic']) }}"
                                               class="btn btn-outline-primary btn-sm"
                                               title="View Topic">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(!$rec['has_scheduled_training'])
                                                <a href="{{ route('trainings.create', ['topic_id' => $rec['topic']->id]) }}"
                                                   class="btn btn-outline-success btn-sm"
                                                   title="Create Training">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="row g-4 mt-2">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Insights & Recommendations</h6>
                        <ul class="mb-0">
                            @if(collect($recommendations)->where('priority', 'critical')->count() > 0)
                                <li class="text-danger">
                                    <strong>{{ collect($recommendations)->where('priority', 'critical')->count() }} critical priority</strong> training(s) requested by 70%+ of employees. Consider scheduling these immediately.
                                </li>
                            @endif
                            @if(collect($recommendations)->where('priority', 'high')->count() > 0)
                                <li class="text-warning">
                                    <strong>{{ collect($recommendations)->where('priority', 'high')->count() }} high priority</strong> training(s) requested by 50%+ of employees.
                                </li>
                            @endif
                            @if(collect($recommendations)->where('has_scheduled_training', true)->count() > 0)
                                <li class="text-success">
                                    <strong>{{ collect($recommendations)->where('has_scheduled_training', true)->count() }} training(s) already scheduled</strong> for the most requested topics.
                                </li>
                            @endif
                            <li class="text-muted">
                                Total of <strong>{{ count($recommendations) }} unique training topics</strong> identified from <strong>{{ $recommendations[0]['total_responses'] ?? 0 }} employee responses</strong> in {{ $year }}.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-x" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="mt-3">No Training Recommendations Available</h5>
                <p class="text-muted">No survey responses have been submitted for {{ $year }} yet.</p>
                <a href="{{ route('surveys.index') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-clipboard-data me-2"></i>View Survey Results
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
