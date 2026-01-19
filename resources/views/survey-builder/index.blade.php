@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="h3">Survey Builder: {{ $surveyTemplate->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('survey-templates.index') }}">Templates</a></li>
                <li class="breadcrumb-item"><a href="{{ route('survey-templates.show', $surveyTemplate) }}">{{ $surveyTemplate->title }}</a></li>
                <li class="breadcrumb-item active">Builder</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Current Questions -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Survey Questions ({{ $surveyTemplate->questions->count() }})</h5>
                    @if($surveyTemplate->questions->count() > 0)
                        <form action="{{ route('survey-builder.add-defaults', $surveyTemplate) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="bi bi-plus-circle me-1"></i>Add All Defaults
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($surveyTemplate->questions->count() > 0)
                        <div id="questionsList">
                            @foreach($surveyTemplate->questions as $question)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-grow-1">
                                                <div class="d-flex gap-2 mb-2">
                                                    <span class="badge bg-secondary">Q{{ $loop->iteration }}</span>
                                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                                    @if($question->pivot->is_required)
                                                        <span class="badge bg-danger">Required</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1"><strong>{{ $question->question_text }}</strong></p>
                                                @if($question->help_text)
                                                    <p class="text-muted small mb-0">{{ $question->help_text }}</p>
                                                @endif
                                            </div>
                                            <div>
                                                <form action="{{ route('survey-builder.remove-question', [$surveyTemplate, $question]) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Remove this question from survey?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted">No questions added yet. Select questions from the available list.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Questions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Available Questions</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($availableQuestions->count() > 0)
                        @foreach($availableQuestions as $question)
                            <div class="card mb-2">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <span class="badge bg-info mb-1">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                            <p class="small mb-0"><strong>{{ Str::limit($question->question_text, 60) }}</strong></p>
                                        </div>
                                        <form action="{{ route('survey-builder.add-question', $surveyTemplate) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small">All questions have been added!</p>
                        <a href="{{ route('survey-questions.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i>Create New Question
                        </a>
                    @endif
                </div>
            </div>

            @if($availableQuestions->count() == 0 && $surveyTemplate->questions->count() == 0)
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <i class="bi bi-lightbulb fs-1 text-warning"></i>
                        <p class="mb-3">No questions in the question bank yet!</p>
                        <p class="small text-muted">Run the seeder to create default questions:</p>
                        <code class="d-block mb-3">php artisan db:seed --class=SurveyQuestionSeeder</code>
                        <a href="{{ route('survey-questions.create') }}" class="btn btn-primary">
                            Create Questions Manually
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
