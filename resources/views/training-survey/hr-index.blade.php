@extends('layouts.app')

@section('title', 'Survey Results - EHRMS')
@section('page-title', 'Training Survey Results')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Training Needs Survey Results</h4>
                    <p class="text-muted mb-0">Year {{ $currentYear }} Survey Responses</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $stats['response_rate'] }}%</h3>
                    <p class="text-muted mb-0 small">Response Rate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $stats['submitted_count'] }}</h3>
                    <p class="text-muted mb-0 small">Submitted</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $stats['pending_count'] }}</h3>
                    <p class="text-muted mb-0 small">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $stats['total_employees'] }}</h3>
                    <p class="text-muted mb-0 small">Total Employees</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Topics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Top Requested Topics</h5>
                </div>
                <div class="card-body">
                    @if(count($topicCounts) > 0)
                        @foreach(array_slice($topicCounts, 0, 10) as $index => $item)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>{{ $index + 1 }}.</strong> {{ $item['topic']->title }}</span>
                                    <span class="badge bg-primary">{{ $item['count'] }} requests</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ ($item['count'] / $stats['submitted_count']) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No topics requested yet</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar-week me-2"></i>Schedule Preferences</h5>
                </div>
                <div class="card-body">
                    @if(isset($stats['schedule_preferences']) && count($stats['schedule_preferences']) > 0)
                        @foreach($stats['schedule_preferences'] as $schedule => $count)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ ucfirst($schedule) }}</span>
                                    <span class="badge bg-success">{{ $count }} employees</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ ($count / $stats['submitted_count']) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">No preferences yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Submissions -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Submissions</h5>
        </div>
        <div class="card-body p-0">
            @if($surveys->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Topics Requested</th>
                                <th>Schedule</th>
                                <th>Format</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->employee->first_name }} {{ $survey->employee->last_name }}</td>
                                    <td>{{ $survey->employee->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $survey->topics->count() }} topics</span>
                                    </td>
                                    <td>{{ ucfirst($survey->preferred_schedule) }}</td>
                                    <td>{{ ucfirst($survey->preferred_format) }}</td>
                                    <td>{{ $survey->updated_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">No survey submissions yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
