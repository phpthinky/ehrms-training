@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <i class="bi bi-check-circle display-1 text-success"></i>
            <h3 class="mt-4">Survey Already Submitted</h3>
            <p class="text-muted">You have already submitted your response for {{ $template->title }}.</p>
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Submitted:</strong> {{ $existingResponse->submitted_at->format('F d, Y g:i A') }}
            </div>
            <p class="text-muted">Thank you for your participation!</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                <i class="bi bi-house-door me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
