@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Survey Analytics: {{ $surveyTemplate->title }}</h1>
            <p class="text-muted">Year: {{ $surveyTemplate->year }}</p>
        </div>
        <div>
            <a href="{{ route('survey-responses.index', $surveyTemplate) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-list-ul me-2"></i>View Responses
            </a>
            <a href="{{ route('survey-templates.show', $surveyTemplate) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Responses</h6>
                    <h2>{{ $totalResponses }}</h2>
                    <small>out of {{ $totalEmployees }} employees</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Response Rate</h6>
                    <h2>{{ $responseRate }}%</h2>
                    <div class="progress bg-white bg-opacity-25 mt-2">
                        <div class="progress-bar bg-white" style="width: {{ $responseRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Questions</h6>
                    <h2>{{ $surveyTemplate->questions->count() }}</h2>
                    <small>survey questions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Breakdown -->
    @if($byDepartment->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Responses by Department</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Responses</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byDepartment as $dept => $count)
                            <tr>
                                <td>{{ $dept }}</td>
                                <td>{{ $count }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($count / $totalResponses) * 100 }}%">
                                            {{ round(($count / $totalResponses) * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Question Analysis -->
    @foreach($questionAnalysis as $analysis)
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-info me-2">{{ ucfirst(str_replace('_', ' ', $analysis['question']->question_type)) }}</span>
                        <strong>{{ $analysis['question']->question_text }}</strong>
                    </div>
                    <span class="text-muted">{{ $analysis['total_responses'] }} responses</span>
                </div>
            </div>
            <div class="card-body">
                @if($analysis['question']->question_type === 'training_programs')
                    <!-- Training Programs Chart -->
                    <h6>Most Requested Training Programs:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            @foreach(array_slice($analysis['data'], 0, 10, true) as $programId => $count)
                                @php
                                    $program = $trainingPrograms->get($programId);
                                @endphp
                                @if($program)
                                    <tr>
                                        <td>{{ $program->program_code }} - {{ $program->program_name }}</td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td style="width: 200px;">
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: {{ ($count / $analysis['total_responses']) * 100 }}%">
                                                    {{ round(($count / $analysis['total_responses']) * 100, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>

                @elseif($analysis['question']->question_type === 'scale' || $analysis['question']->question_type === 'number')
                    <!-- Numeric Average -->
                    <div class="alert alert-info">
                        <strong>Average:</strong> {{ $analysis['average'] ?? 'N/A' }}
                    </div>

                @elseif($analysis['question']->question_type === 'radio' || $analysis['question']->question_type === 'checkbox')
                    <!-- Choice Distribution -->
                    <div class="table-responsive">
                        <table class="table table-sm">
                            @foreach($analysis['data'] as $option => $count)
                                <tr>
                                    <td>{{ $option }}</td>
                                    <td class="text-end">{{ $count }}</td>
                                    <td style="width: 200px;">
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ ($count / $analysis['total_responses']) * 100 }}%">
                                                {{ round(($count / $analysis['total_responses']) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                @elseif($analysis['question']->question_type === 'text' || $analysis['question']->question_type === 'textarea')
                    <!-- Text Responses -->
                    <p class="text-muted">{{ $analysis['total_responses'] }} text responses. <a href="{{ route('survey-responses.index', $surveyTemplate) }}">View individual responses</a></p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
