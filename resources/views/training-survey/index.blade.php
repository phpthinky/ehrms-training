@extends('layouts.app')

@section('title', 'Training Surveys - EHRMS')
@section('page-title', 'Training Needs Survey Results')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                <i class="bi bi-clipboard-data text-primary me-2"></i>Training Needs Survey {{ $year }}
            </h4>
            <p class="text-muted mb-0">Employee training preferences and interests</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('training-surveys.index') }}" method="GET" class="d-inline">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-people text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Employees</small>
                            <h4 class="mb-0" style="font-weight: 600;">{{ $stats['total_employees'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-circle text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Submitted</small>
                            <h4 class="mb-0" style="font-weight: 600;">{{ $stats['submitted_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-clock text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Pending</small>
                            <h4 class="mb-0" style="font-weight: 600;">{{ $stats['pending_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-percent text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Response Rate</small>
                            <h4 class="mb-0" style="font-weight: 600;">{{ $stats['response_rate'] }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Survey Responses -->
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0" style="font-weight: 600;">Survey Responses ({{ $surveys->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($surveys->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Top Topic</th>
                                        <th>Submitted</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surveys as $survey)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2" style="width: 35px; height: 35px; border-radius: 8px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                        {{ strtoupper(substr($survey->employee->first_name, 0, 1) . substr($survey->employee->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $survey->employee->getFullNameAttribute() }}</div>
                                                        <small class="text-muted">{{ $survey->employee->employee_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $survey->employee->department->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if($survey->topic)
                                                    <span class="badge bg-primary-subtle text-primary">{{ $survey->topic->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $survey->submitted_at->format('M d, Y') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('training-surveys.show', $survey) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-data text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">No survey responses yet for {{ $year }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Requested Topics -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0" style="font-weight: 600;">
                        <i class="bi bi-trophy text-warning me-2"></i>Top Requested Topics
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($stats['topic_stats']) > 0)
                        @foreach(array_slice($stats['topic_stats'], 0, 10) as $index => $stat)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2" style="min-width: 24px;">{{ $index + 1 }}</span>
                                    <span class="fw-semibold small">{{ $stat['topic']->name }}</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-subtle text-success">{{ $stat['count'] }}</span>
                                    <small class="text-muted ms-1">({{ $stat['percentage'] }}%)</small>
                                </div>
                            </div>
                            @if($index < 9)
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar" style="width: {{ $stat['percentage'] }}%"></div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">No data available yet</p>
                    @endif
                </div>
            </div>

            <!-- Schedule Preferences -->
            <div class="card border-0 mt-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0" style="font-weight: 600;">
                        <i class="bi bi-calendar text-info me-2"></i>Schedule Preferences
                    </h6>
                </div>
                <div class="card-body">
                    @if($stats['schedule_preferences']->count() > 0)
                        @foreach($stats['schedule_preferences'] as $schedule => $count)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">{{ ucwords(str_replace('_', ' ', $schedule)) }}</span>
                                <strong>{{ $count }}</strong>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">No data available</p>
                    @endif
                </div>
            </div>

            <!-- Format Preferences -->
            <div class="card border-0 mt-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0" style="font-weight: 600;">
                        <i class="bi bi-cast text-success me-2"></i>Format Preferences
                    </h6>
                </div>
                <div class="card-body">
                    @if($stats['format_preferences']->count() > 0)
                        @foreach($stats['format_preferences'] as $format => $count)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">{{ ucwords(str_replace('_', ' ', $format)) }}</span>
                                <strong>{{ $count }}</strong>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
