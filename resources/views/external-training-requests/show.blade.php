@extends('layouts.app')

@section('title', 'External Training Request Details - EHRMS')
@section('page-title', 'Request Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                @if(auth()->user()->isStaff())
                    <a href="{{ route('external-training-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to All Requests
                    </a>
                @else
                    <a href="{{ route('my-external-training-requests') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to My Requests
                    </a>
                @endif
            </div>

            <!-- Status Banner -->
            <div class="alert alert-{{ $externalTrainingRequest->status_badge }} border-0 mb-4">
                <div class="d-flex align-items-center">
                    @switch($externalTrainingRequest->status)
                        @case('draft')
                            <i class="bi bi-file-earmark me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Draft</strong>
                                <p class="mb-0 small">This request has not been submitted yet.</p>
                            </div>
                            @break
                        @case('pending')
                            <i class="bi bi-hourglass-split me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Pending Review</strong>
                                <p class="mb-0 small">Your request is being reviewed by HR.</p>
                            </div>
                            @break
                        @case('approved')
                            <i class="bi bi-check-circle me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Approved</strong>
                                <p class="mb-0 small">
                                    Approved on {{ $externalTrainingRequest->reviewed_at?->format('M d, Y') }}
                                    by {{ $externalTrainingRequest->reviewer?->name ?? 'HR' }}
                                </p>
                            </div>
                            @break
                        @case('rejected')
                            <i class="bi bi-x-circle me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Rejected</strong>
                                <p class="mb-0 small">{{ $externalTrainingRequest->rejection_reason }}</p>
                            </div>
                            @break
                        @case('completed')
                            <i class="bi bi-trophy me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Completed</strong>
                                <p class="mb-0 small">Training has been completed successfully.</p>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>

            <!-- Training Details Card -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-journal-bookmark text-primary me-2"></i>Training Details
                    </h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $externalTrainingRequest->training_title }}</h4>

                    @if($externalTrainingRequest->training_description)
                        <p class="text-muted mb-4">{{ $externalTrainingRequest->training_description }}</p>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-building text-primary me-3" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Training Provider</small>
                                    <span class="fw-semibold">{{ $externalTrainingRequest->training_provider ?? 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-geo-alt text-primary me-3" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Venue</small>
                                    <span class="fw-semibold">{{ $externalTrainingRequest->training_venue ?? 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-calendar-event text-primary me-3" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Schedule</small>
                                    <span class="fw-semibold">
                                        {{ $externalTrainingRequest->start_date->format('M d, Y') }}
                                        @if($externalTrainingRequest->start_date != $externalTrainingRequest->end_date)
                                            - {{ $externalTrainingRequest->end_date->format('M d, Y') }}
                                        @endif
                                    </span>
                                    <small class="text-muted d-block">({{ $externalTrainingRequest->duration_days }} day{{ $externalTrainingRequest->duration_days > 1 ? 's' : '' }})</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-currency-dollar text-primary me-3" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Estimated Cost</small>
                                    <span class="fw-semibold">{{ $externalTrainingRequest->formatted_cost }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($externalTrainingRequest->purpose)
                        <hr class="my-4">
                        <div>
                            <h6 class="fw-semibold mb-2">Purpose / Justification</h6>
                            <p class="mb-0 text-muted">{{ $externalTrainingRequest->purpose }}</p>
                        </div>
                    @endif

                    @if($externalTrainingRequest->hr_remarks)
                        <hr class="my-4">
                        <div class="bg-light rounded p-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-chat-left-text me-2"></i>HR Remarks
                            </h6>
                            <p class="mb-0">{{ $externalTrainingRequest->hr_remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-paperclip text-primary me-2"></i>Uploaded Documents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Request Form -->
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-text text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Request Form</h6>
                                    @if($externalTrainingRequest->request_form_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->request_form_name }}">
                                            {{ $externalTrainingRequest->request_form_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'request_form']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-muted mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Department Head Letter -->
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-envelope-paper text-success mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Dept. Head Letter</h6>
                                    @if($externalTrainingRequest->department_head_letter_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->department_head_letter_name }}">
                                            {{ $externalTrainingRequest->department_head_letter_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'department_head_letter']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-muted mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Requesting Party Document -->
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark-check text-warning mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Requesting Party</h6>
                                    @if($externalTrainingRequest->requesting_party_document_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->requesting_party_document_name }}">
                                            {{ $externalTrainingRequest->requesting_party_document_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'requesting_party_document']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-muted mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($externalTrainingRequest->canBeEdited() && !auth()->user()->isStaff())
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <a href="{{ route('external-training-requests.edit', $externalTrainingRequest) }}"
                               class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Edit Request
                            </a>
                            @if($externalTrainingRequest->status === 'draft')
                                <form action="{{ route('external-training-requests.destroy', $externalTrainingRequest) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this draft?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash me-2"></i>Delete Draft
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- HR Actions -->
            @if(auth()->user()->isStaff() && $externalTrainingRequest->canBeReviewed())
                <div class="card border-0">
                    <div class="card-body">
                        <a href="{{ route('external-training-requests.review', $externalTrainingRequest) }}"
                           class="btn btn-primary">
                            <i class="bi bi-clipboard-check me-2"></i>Review Request
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isStaff() && $externalTrainingRequest->status === 'approved')
                <div class="card border-0">
                    <div class="card-body">
                        <form action="{{ route('external-training-requests.mark-completed', $externalTrainingRequest) }}"
                              method="POST"
                              onsubmit="return confirm('Mark this training as completed?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Mark as Completed
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Requestor Info -->
            <div class="card border-0 mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-badge text-primary me-2"></i>Requestor
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px; font-weight: 600;">
                            {{ strtoupper(substr($externalTrainingRequest->employee->first_name, 0, 1) . substr($externalTrainingRequest->employee->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $externalTrainingRequest->employee->full_name }}</h6>
                            <small class="text-muted">{{ $externalTrainingRequest->employee->position ?? 'Employee' }}</small>
                        </div>
                    </div>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted ps-0">Employee #:</td>
                            <td class="pe-0">{{ $externalTrainingRequest->employee->employee_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Department:</td>
                            <td class="pe-0">{{ $externalTrainingRequest->employee->department->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-clock-history text-primary me-2"></i>Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Created</small>
                                <p class="mb-0">{{ $externalTrainingRequest->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($externalTrainingRequest->status !== 'draft')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Submitted</small>
                                    <p class="mb-0">{{ $externalTrainingRequest->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($externalTrainingRequest->reviewed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $externalTrainingRequest->status === 'approved' ? 'success' : 'danger' }}"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ ucfirst($externalTrainingRequest->status) }}</small>
                                    <p class="mb-0">{{ $externalTrainingRequest->reviewed_at->format('M d, Y h:i A') }}</p>
                                    <small class="text-muted">by {{ $externalTrainingRequest->reviewer?->name ?? 'HR' }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Linked Training -->
            @if($externalTrainingRequest->training)
                <div class="card border-0 mt-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-link-45deg text-primary me-2"></i>Linked Training
                        </h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('trainings.show', $externalTrainingRequest->training) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-journal-bookmark text-primary me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <span class="fw-semibold">{{ $externalTrainingRequest->training->title }}</span>
                                    <small class="text-muted d-block">View training record</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 8px;
    bottom: -12px;
    width: 2px;
    background: #e2e8f0;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 4px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px currentColor;
}
</style>
@endpush
@endsection
