@extends('layouts.app')

@section('title', 'Training Report - EHRMS')
@section('page-title', 'Training Participation Report')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.training') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="internal" {{ request('type') == 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="external" {{ request('type') == 'external' ? 'selected' : '' }}>External</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('reports.training') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $totalTrainings }}</h3>
                    <p class="text-muted mb-0 small">Total Trainings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $totalParticipants }}</h3>
                    <p class="text-muted mb-0 small">Total Participants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $totalAttended }}</h3>
                    <p class="text-muted mb-0 small">Attended</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $attendanceRate }}%</h3>
                    <p class="text-muted mb-0 small">Attendance Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Training List</h5>
            <form action="{{ route('reports.export.training') }}" method="GET" class="d-inline">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="type" value="{{ request('type') }}">
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            @if($trainings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Training Title</th>
                                <th>Topic</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Participants</th>
                                <th>Attended</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainings as $training)
                                @php
                                    $total = $training->attendances->count();
                                    $attended = $training->attendances->where('status', 'attended')->count();
                                    $rate = $total > 0 ? round(($attended / $total) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->topic->title ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary-subtle text-primary">{{ ucfirst($training->type) }}</span></td>
                                    <td>{{ date('M d, Y', strtotime($training->start_date)) }}</td>
                                    <td>{{ $total }}</td>
                                    <td>{{ $attended }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $rate }}%</span>
                                            <div class="progress" style="width: 60px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $rate }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">No trainings found</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
