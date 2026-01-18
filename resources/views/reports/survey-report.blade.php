@extends('layouts.app')

@section('title', 'Survey Analysis Report - EHRMS')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $responseRate }}%</h3>
                    <p class="text-muted mb-0 small">Response Rate</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $submittedCount }}</h3>
                    <p class="text-muted mb-0 small">Submitted</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $pendingCount }}</h3>
                    <p class="text-muted mb-0 small">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Top Requested Training Topics</h5>
        </div>
        <div class="card-body">
            @foreach($topTopics as $index => $item)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $index + 1 }}. {{ $item['topic']->title }}</span>
                        <span class="badge bg-primary">{{ $item['count'] }} requests</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($item['count'] / $submittedCount) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
