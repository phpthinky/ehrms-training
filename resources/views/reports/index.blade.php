@extends('layouts.app')

@section('title', 'Reports - EHRMS')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 700;">
                                <i class="bi bi-file-earmark-bar-graph me-2"></i>Reports & Analytics
                            </h3>
                            <p class="mb-0 opacity-75">
                                Generate comprehensive reports and export data for analysis
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-graph-up-arrow" style="font-size: 4rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary bg-opacity-10 rounded p-3">
                                <i class="bi bi-people fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Employees</h6>
                            <h3 class="mb-0">{{ $stats['totalEmployees'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-success bg-opacity-10 rounded p-3">
                                <i class="bi bi-journal-bookmark fs-3 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Trainings</h6>
                            <h3 class="mb-0">{{ $stats['totalTrainings'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-warning bg-opacity-10 rounded p-3">
                                <i class="bi bi-person-check fs-3 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Attendance Records</h6>
                            <h3 class="mb-0">{{ $stats['totalAttendance'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-info bg-opacity-10 rounded p-3">
                                <i class="bi bi-clipboard-data fs-3 text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Survey Responses</h6>
                            <h3 class="mb-0">{{ $stats['totalSurveys'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="row g-4">
        <!-- Training Report -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-calendar-event fs-2 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2">Training Participation Report</h4>
                            <p class="text-muted mb-0">
                                Comprehensive overview of all training programs with attendance statistics, 
                                participant lists, and completion rates.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by date range</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by training type</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by topic</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Attendance rate analysis</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Export to CSV</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.training') }}" class="btn btn-primary w-100">
                            <i class="bi bi-file-earmark-text me-2"></i>Generate Training Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Report -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon-box bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-person-badge fs-2 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2">Employee Training History</h4>
                            <p class="text-muted mb-0">
                                Individual employee training records showing all attended trainings, 
                                certificates earned, and training hours.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by department</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by status</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Filter by rank level</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Certificate tracking</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Export to CSV</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.employee') }}" class="btn btn-success w-100">
                            <i class="bi bi-file-earmark-person me-2"></i>Generate Employee Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Survey Report -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-clipboard-data fs-2 text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2">Training Survey Analysis</h4>
                            <p class="text-muted mb-0">
                                Analyze training needs survey results with topic popularity rankings, 
                                preference distributions, and response rates.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Topic popularity rankings</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Schedule preferences</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Format preferences</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Department breakdown</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Response rate tracking</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.survey') }}" class="btn btn-warning w-100">
                            <i class="bi bi-graph-up me-2"></i>Generate Survey Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Report -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon-box bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-building fs-2 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2">Department Statistics</h4>
                            <p class="text-muted mb-0">
                                Compare training participation across departments with employee counts, 
                                training totals, and average trainings per employee.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Employee distribution</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Training counts per department</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Average trainings calculation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Attendance statistics</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Comparative analysis</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.department') }}" class="btn btn-info w-100">
                            <i class="bi bi-pie-chart me-2"></i>Generate Department Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Export Options -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-download me-2"></i>Quick Export Options
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <form action="{{ route('reports.export.training') }}" method="GET" class="d-flex gap-3 align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label small">Training Report (All Time)</label>
                                    <input type="hidden" name="export" value="csv">
                                </div>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export to CSV
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('reports.export.employee') }}" method="GET" class="d-flex gap-3 align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label small">Employee Report (All Employees)</label>
                                    <input type="hidden" name="export" value="csv">
                                </div>
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export to CSV
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
