@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Question Bank</h1>
            <p class="text-muted">Manage reusable survey questions</p>
        </div>
        <a href="{{ route('survey-questions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Question
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Used In</th>
                            <th>Default</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($question->question_text, 80) }}</strong>
                                    @if($question->help_text)
                                        <br><small class="text-muted">{{ Str::limit($question->help_text, 60) }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $questionTypes[$question->question_type] ?? $question->question_type }}</span></td>
                                <td><span class="badge bg-secondary">{{ $question->templates_count }} templates</span></td>
                                <td>
                                    @if($question->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('survey-questions.edit', $question) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($question->templates_count == 0)
                                            <form action="{{ route('survey-questions.destroy', $question) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this question?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">No questions yet. Add your first question or run the seeder!</p>
                                    <code>php artisan db:seed --class=SurveyQuestionSeeder</code>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $questions->links() }}</div>
        </div>
    </div>
</div>
@endsection
