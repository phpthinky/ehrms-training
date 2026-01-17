@extends('layouts.app')

@section('title', 'Training Needs Survey - EHRMS')
@section('page-title', 'Training Needs Survey')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Survey Header -->
            <div class="card border-0 mb-4">
                <div class="card-body text-center py-5" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05));">
                    <div class="mb-3">
                        <i class="bi bi-clipboard-data text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        Training Needs Survey {{ $currentYear }}
                    </h3>
                    <p class="text-muted mb-0">Help us plan better training programs by sharing your learning interests</p>
                </div>
            </div>

            @if($existingSurvey && $existingSurvey->status === 'submitted')
                <!-- Already Submitted -->
                <div class="card border-0">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h5 class="mb-3">Survey Already Submitted!</h5>
                        <p class="text-muted mb-4">
                            You have already submitted your training needs survey for {{ $currentYear }}.
                            <br>
                            <small>Submitted on: {{ $existingSurvey->submitted_at->format('F d, Y') }}</small>
                        </p>

                        <!-- Show submitted topics -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Your Selected Topics:</h6>
                                @php
                                    $selectedTopics = json_decode($existingSurvey->additional_topics, true) ?? [];
                                    $topicsList = \App\Models\TrainingTopic::whereIn('id', $selectedTopics)->get();
                                @endphp
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    @foreach($topicsList as $topic)
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                            <i class="bi bi-check2 me-1"></i>{{ $topic->title }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            @else
                <!-- Survey Form -->
                <div class="card border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('training-survey.submit') }}" method="POST">
                            @csrf

                            <!-- Instructions -->
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>Instructions:</strong>
                                        <ul class="mb-0 mt-2 small">
                                            <li>Select 1 to 5 training topics you're interested in</li>
                                            <li>You can suggest additional topics in the text field</li>
                                            <li>Indicate your preferred schedule and format</li>
                                            <li>You can only submit once per year</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Training Topics Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Select Training Topics <span class="text-danger">*</span>
                                    <small class="text-muted">(Choose 1-5 topics)</small>
                                </label>
                                
                                <div class="row g-3">
                                    @foreach($topics as $topic)
                                        <div class="col-md-6">
                                            <div class="form-check card p-3 h-100 border hover-shadow" style="transition: all 0.2s;">
                                                <input class="form-check-input" type="checkbox" name="topics[]" value="{{ $topic->id }}" id="topic{{ $topic->id }}" {{ in_array($topic->id, old('topics', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="topic{{ $topic->id }}" style="cursor: pointer;">
                                                    <div class="fw-semibold">{{ $topic->title }}</div>
                                                    @if($topic->description)
                                                        <small class="text-muted">{{ $topic->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @error('topics')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Please select at least 1 topic, maximum 5 topics</small>
                            </div>

                            <!-- Additional Topics -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Additional Topics (Optional)</label>
                                <textarea name="additional_topics" class="form-control" rows="3" placeholder="Suggest other training topics not listed above">{{ old('additional_topics') }}</textarea>
                                <small class="text-muted">If your desired training topic is not listed, you can suggest it here</small>
                            </div>

                            <!-- Preferred Schedule -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Preferred Schedule</label>
                                <select name="preferred_schedule" class="form-select">
                                    <option value="any" {{ old('preferred_schedule') == 'any' ? 'selected' : '' }}>Any time</option>
                                    <option value="weekday_morning" {{ old('preferred_schedule') == 'weekday_morning' ? 'selected' : '' }}>Weekday Morning</option>
                                    <option value="weekday_afternoon" {{ old('preferred_schedule') == 'weekday_afternoon' ? 'selected' : '' }}>Weekday Afternoon</option>
                                    <option value="weekend" {{ old('preferred_schedule') == 'weekend' ? 'selected' : '' }}>Weekend</option>
                                </select>
                            </div>

                            <!-- Preferred Format -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Preferred Training Format</label>
                                <select name="preferred_format" class="form-select">
                                    <option value="any" {{ old('preferred_format') == 'any' ? 'selected' : '' }}>Any format</option>
                                    <option value="in_person" {{ old('preferred_format') == 'in_person' ? 'selected' : '' }}>In-Person</option>
                                    <option value="online" {{ old('preferred_format') == 'online' ? 'selected' : '' }}>Online/Virtual</option>
                                    <option value="hybrid" {{ old('preferred_format') == 'hybrid' ? 'selected' : '' }}>Hybrid (Both)</option>
                                </select>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Remarks (Optional)</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Any other comments or suggestions">{{ old('remarks') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('dashboard') }}" class="btn btn-light px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-2"></i>Submit Survey
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-question-circle text-primary me-2"></i>Why Fill This Survey?
                    </h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Help HR plan relevant training programs</li>
                        <li class="mb-2">Get training that matches your career goals</li>
                        <li class="mb-2">Improve your skills and competencies</li>
                        <li class="mb-2">Voice your professional development needs</li>
                        <li class="mb-2">Contribute to organizational growth</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Survey Tips
                    </h6>
                    <div class="small text-muted">
                        <p class="mb-2"><strong>Be Specific:</strong> Choose topics that truly interest you</p>
                        <p class="mb-2"><strong>Think Long-term:</strong> Consider your career development</p>
                        <p class="mb-2"><strong>Be Realistic:</strong> Select topics you can commit to</p>
                        <p class="mb-0"><strong>Suggest Topics:</strong> Don't hesitate to propose new training areas</p>
                    </div>
                </div>
            </div>

            @if(!$existingSurvey || $existingSurvey->status !== 'submitted')
                <div class="alert alert-warning mt-3 mb-0">
                    <small>
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Reminder:</strong> Survey can only be submitted once per year. Please review your selections before submitting.
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-shadow:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #3b82f6 !important;
}
.form-check-input:checked ~ .form-check-label {
    color: #1e40af;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="topics[]"]');
    const maxSelection = 5;
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="topics[]"]:checked').length;
            
            if (checkedCount >= maxSelection) {
                checkboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.disabled = true;
                        cb.parentElement.style.opacity = '0.5';
                    }
                });
            } else {
                checkboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.parentElement.style.opacity = '1';
                });
            }
        });
    });
});
</script>
@endpush
@endsection
