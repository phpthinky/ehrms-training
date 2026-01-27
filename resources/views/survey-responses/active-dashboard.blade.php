@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Annual Survey Results</h1>
            <p class="text-muted">{{ $activeTemplates->count() }} active surveys</p>
        </div>
        <a href="{{ route('survey-templates.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-gear me-2"></i>Manage Templates
        </a>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Active Surveys</h6>
                    <h2>{{ $activeTemplates->count() }}</h2>
                    <small>currently collecting responses</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Responses</h6>
                    <h2>{{ $activeTemplates->sum('submitted_count') }}</h2>
                    <small>across all surveys</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Active Employees</h6>
                    <h2>{{ $totalEmployees }}</h2>
                    <small>eligible to respond</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Surveys List -->
    <div class="row">
        @foreach($activeTemplates as $template)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $template->title }}</h5>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <i class="bi bi-calendar me-1"></i>Year: {{ $template->year }}
                        </p>
                        @if($template->description)
                            <p class="small mb-3">{{ Str::limit($template->description, 100) }}</p>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Responses:</span>
                            <strong>{{ $template->submitted_count }}</strong>
                        </div>

                        @php
                            $responseRate = $totalEmployees > 0 ? round(($template->submitted_count / $totalEmployees) * 100, 1) : 0;
                        @endphp
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" style="width: {{ $responseRate }}%">
                                {{ $responseRate }}%
                            </div>
                        </div>
                        <small class="text-muted">{{ $template->submitted_count }} of {{ $totalEmployees }} employees responded</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <a href="{{ route('survey-responses.analytics', $template) }}" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-bar-chart me-1"></i>Analytics
                            </a>
                            <a href="{{ route('survey-responses.index', $template) }}" class="btn btn-outline-primary">
                                <i class="bi bi-list"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
