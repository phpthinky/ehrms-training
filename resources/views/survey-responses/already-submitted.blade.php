@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <i class="bi bi-check-circle display-1 text-success"></i>
            <h3 class="mt-4">Survey Already Submitted</h3>
            <p class="text-muted">You have already submitted your response for <strong>{{ $template->title }}</strong>.</p>
            @if($existingResponse->submitted_at)
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Submitted:</strong> {{ $existingResponse->submitted_at->format('F d, Y g:i A') }}
                </div>
            @endif
            <p class="text-muted">Thank you for your participation!</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                @if(isset($activeTemplates) && $activeTemplates->count() > 1)
                    <a href="{{ route('survey.form') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-check me-2"></i>View Other Surveys
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house-door me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
