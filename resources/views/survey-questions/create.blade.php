@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="h3">Add Question to Bank</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('survey-questions.index') }}">Question Bank</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('survey-questions.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="question_type" class="form-label">Question Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('question_type') is-invalid @enderror"
                                    id="question_type" name="question_type" required>
                                <option value="">Select type...</option>
                                @foreach($questionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('question_type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('question_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror"
                                      id="question_text" name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="optionsContainer" style="display: none;">
                            <label class="form-label">Options (for checkbox/radio)</label>
                            <div id="optionsList">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="options[]" placeholder="Option 1">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOption()">
                                <i class="bi bi-plus-circle me-1"></i>Add Option
                            </button>
                        </div>

                        <div class="mb-3">
                            <label for="help_text" class="form-label">Help Text</label>
                            <input type="text" class="form-control @error('help_text') is-invalid @enderror"
                                   id="help_text" name="help_text" value="{{ old('help_text') }}"
                                   placeholder="Optional hint or instruction">
                            @error('help_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default"
                                       name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    Mark as Default Question
                                </label>
                            </div>
                            <small class="text-muted">Default questions appear in the "Add All Defaults" feature</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Add Question
                            </button>
                            <a href="{{ route('survey-questions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('question_type').addEventListener('change', function() {
    const optionsContainer = document.getElementById('optionsContainer');
    if (this.value === 'checkbox' || this.value === 'radio') {
        optionsContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
    }
});

let optionCount = 1;
function addOption() {
    optionCount++;
    const optionsList = document.getElementById('optionsList');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="options[]" placeholder="Option ${optionCount}">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-trash"></i>
        </button>
    `;
    optionsList.appendChild(div);
}
</script>
@endsection
