@extends('layouts.app')

@section('content')
<div class="container px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>{{ $template->title }}</h4>
                </div>
                <div class="card-body">
                    @if($template->description)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>{{ $template->description }}
                        </div>
                    @endif

                    <form action="{{ route('survey.submit') }}" method="POST" id="surveyForm">
                        @csrf

                        @foreach($template->questions as $index => $question)
                            <div class="mb-4 pb-3 border-bottom">
                                <label class="form-label fw-bold">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if($question->pivot->is_required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                @if($question->help_text)
                                    <p class="text-muted small"><i class="bi bi-info-circle"></i> {{ $question->help_text }}</p>
                                @endif

                                @php
                                    $fieldName = 'question_' . $question->id;
                                    $oldValue = old($fieldName, ($existingResponse && $existingResponse->response_data) ? ($existingResponse->response_data[$question->id] ?? null) : null);
                                @endphp

                                @if($question->question_type === 'training_programs')
                                    <div class="row">
                                        @foreach($trainingPrograms as $program)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="{{ $fieldName }}[]" value="{{ $program->id }}"
                                                           id="program_{{ $program->id }}"
                                                           {{ is_array($oldValue) && in_array($program->id, $oldValue) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="program_{{ $program->id }}">
                                                        {{ $program->program_code }} - {{ $program->program_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($question->question_type === 'checkbox')
                                    @foreach($question->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="{{ $fieldName }}[]" value="{{ $option }}"
                                                   id="{{ $fieldName }}_{{ $loop->index }}"
                                                   {{ is_array($oldValue) && in_array($option, $oldValue) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $fieldName }}_{{ $loop->index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach

                                @elseif($question->question_type === 'radio')
                                    @foreach($question->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="{{ $fieldName }}" value="{{ $option }}"
                                                   id="{{ $fieldName }}_{{ $loop->index }}"
                                                   {{ $oldValue == $option ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $fieldName }}_{{ $loop->index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach

                                @elseif($question->question_type === 'text')
                                    <input type="text" class="form-control @error($fieldName) is-invalid @enderror"
                                           name="{{ $fieldName }}" value="{{ $oldValue }}">

                                @elseif($question->question_type === 'textarea')
                                    <textarea class="form-control @error($fieldName) is-invalid @enderror"
                                              name="{{ $fieldName }}" rows="4">{{ $oldValue }}</textarea>

                                @elseif($question->question_type === 'number')
                                    <input type="number" class="form-control @error($fieldName) is-invalid @enderror"
                                           name="{{ $fieldName }}" value="{{ $oldValue }}">

                                @elseif($question->question_type === 'scale')
                                    <div class="btn-group" role="group">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" name="{{ $fieldName }}"
                                                   value="{{ $i }}" id="{{ $fieldName }}_{{ $i }}"
                                                   {{ $oldValue == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="{{ $fieldName }}_{{ $i }}">{{ $i }}</label>
                                        @endfor
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">1 (Low)</small>
                                        <small class="text-muted">5 (High)</small>
                                    </div>
                                @endif

                                @error($fieldName)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Survey
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
