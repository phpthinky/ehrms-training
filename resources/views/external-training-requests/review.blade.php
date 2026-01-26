@extends('layouts.app')

@section('title', 'Review External Training Request - EHRMS')
@section('page-title', 'Review Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('external-training-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to All Requests
                </a>
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
                            <div class="card border h-100 {{ $externalTrainingRequest->request_form_path ? 'border-success' : 'border-danger' }}">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-text {{ $externalTrainingRequest->request_form_path ? 'text-success' : 'text-danger' }} mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Request Form</h6>
                                    @if($externalTrainingRequest->request_form_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->request_form_name }}">
                                            {{ $externalTrainingRequest->request_form_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'request_form']) }}"
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-danger mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Department Head Letter -->
                        <div class="col-md-4">
                            <div class="card border h-100 {{ $externalTrainingRequest->department_head_letter_path ? 'border-success' : 'border-danger' }}">
                                <div class="card-body text-center">
                                    <i class="bi bi-envelope-paper {{ $externalTrainingRequest->department_head_letter_path ? 'text-success' : 'text-danger' }} mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Dept. Head Letter</h6>
                                    @if($externalTrainingRequest->department_head_letter_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->department_head_letter_name }}">
                                            {{ $externalTrainingRequest->department_head_letter_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'department_head_letter']) }}"
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-danger mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Requesting Party Document -->
                        <div class="col-md-4">
                            <div class="card border h-100 {{ $externalTrainingRequest->requesting_party_document_path ? 'border-success' : 'border-danger' }}">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark-check {{ $externalTrainingRequest->requesting_party_document_path ? 'text-success' : 'text-danger' }} mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="mb-2">Requesting Party</h6>
                                    @if($externalTrainingRequest->requesting_party_document_path)
                                        <p class="small text-muted mb-2 text-truncate" title="{{ $externalTrainingRequest->requesting_party_document_name }}">
                                            {{ $externalTrainingRequest->requesting_party_document_name }}
                                        </p>
                                        <a href="{{ route('external-training-requests.download', [$externalTrainingRequest, 'requesting_party_document']) }}"
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    @else
                                        <p class="small text-danger mb-0">Not uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-clipboard-check text-primary me-2"></i>Review Decision
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('external-training-requests.process-review', $externalTrainingRequest) }}" method="POST" id="reviewForm">
                        @csrf
                        @method('POST')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">HR Remarks (Optional)</label>
                            <textarea name="hr_remarks" class="form-control @error('hr_remarks') is-invalid @enderror"
                                rows="3" placeholder="Add any notes or comments about this request">{{ old('hr_remarks') }}</textarea>
                            @error('hr_remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="rejectionReasonDiv" style="display: none;">
                            <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="rejectionReason" class="form-control @error('rejection_reason') is-invalid @enderror"
                                rows="3" placeholder="Please provide a clear reason for rejecting this request">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">The employee will see this reason and can resubmit with corrections.</small>
                        </div>

                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('external-training-requests.index') }}" class="btn btn-light px-4">
                                Cancel
                            </a>
                            <button type="submit" name="action" value="reject" class="btn btn-danger px-4" id="rejectBtn">
                                <i class="bi bi-x-circle me-2"></i>Reject
                            </button>
                            <button type="submit" name="action" value="approve" class="btn btn-success px-4" id="approveBtn">
                                <i class="bi bi-check-circle me-2"></i>Approve
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

            <!-- Review Checklist -->
            <div class="card border-0 mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-list-check text-primary me-2"></i>Review Checklist
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label small" for="check1">Training is relevant to employee's role</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label small" for="check2">Request form is properly filled</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label small" for="check3">Department head endorsement verified</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check4">
                        <label class="form-check-label small" for="check4">Requesting party document valid</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check5">
                        <label class="form-check-label small" for="check5">Budget/cost is acceptable</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check6">
                        <label class="form-check-label small" for="check6">Schedule does not conflict with operations</label>
                    </div>
                </div>
            </div>

            <!-- Request Info -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle text-primary me-2"></i>Request Info
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted ps-0">Request ID:</td>
                            <td class="pe-0">#{{ $externalTrainingRequest->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Submitted:</td>
                            <td class="pe-0">{{ $externalTrainingRequest->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Documents:</td>
                            <td class="pe-0">
                                @if($externalTrainingRequest->hasAllDocuments())
                                    <span class="text-success"><i class="bi bi-check-circle me-1"></i>Complete</span>
                                @else
                                    <span class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>Incomplete</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectBtn = document.getElementById('rejectBtn');
    const approveBtn = document.getElementById('approveBtn');
    const rejectionReasonDiv = document.getElementById('rejectionReasonDiv');
    const rejectionReason = document.getElementById('rejectionReason');
    const form = document.getElementById('reviewForm');

    rejectBtn.addEventListener('click', function(e) {
        rejectionReasonDiv.style.display = 'block';
        rejectionReason.required = true;

        if (!rejectionReason.value.trim()) {
            e.preventDefault();
            rejectionReason.focus();
            rejectionReason.classList.add('is-invalid');
            return false;
        }
    });

    approveBtn.addEventListener('click', function(e) {
        rejectionReasonDiv.style.display = 'none';
        rejectionReason.required = false;
        rejectionReason.classList.remove('is-invalid');
    });

    // Show rejection reason on validation error
    @if(old('action') === 'reject' || $errors->has('rejection_reason'))
        rejectionReasonDiv.style.display = 'block';
        rejectionReason.required = true;
    @endif
});
</script>
@endpush
@endsection
