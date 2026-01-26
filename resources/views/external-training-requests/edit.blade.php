@extends('layouts.app')

@section('title', 'Edit External Training Request - EHRMS')
@section('page-title', 'Edit Request')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('my-external-training-requests') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to My Requests
                </a>
            </div>

            @if($externalTrainingRequest->status === 'rejected')
                <!-- Rejection Notice -->
                <div class="alert alert-danger border-0 mb-4">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-circle me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Request Rejected</strong>
                            <p class="mb-0 mt-1">{{ $externalTrainingRequest->rejection_reason }}</p>
                            <small class="text-muted">You can update your request and resubmit.</small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-pencil text-primary me-2"></i>Edit External Training Request
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('external-training-requests.update', $externalTrainingRequest) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Training Details Section -->
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="bi bi-journal-bookmark me-2"></i>Training Details
                        </h6>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Training Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="training_title" class="form-control @error('training_title') is-invalid @enderror"
                                    value="{{ old('training_title', $externalTrainingRequest->training_title) }}" required>
                                @error('training_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Training Description</label>
                                <textarea name="training_description" class="form-control @error('training_description') is-invalid @enderror"
                                    rows="3">{{ old('training_description', $externalTrainingRequest->training_description) }}</textarea>
                                @error('training_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Training Provider</label>
                                <input type="text" name="training_provider" class="form-control @error('training_provider') is-invalid @enderror"
                                    value="{{ old('training_provider', $externalTrainingRequest->training_provider) }}">
                                @error('training_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Venue</label>
                                <input type="text" name="training_venue" class="form-control @error('training_venue') is-invalid @enderror"
                                    value="{{ old('training_venue', $externalTrainingRequest->training_venue) }}">
                                @error('training_venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date', $externalTrainingRequest->start_date->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date', $externalTrainingRequest->end_date->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estimated Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="estimated_cost" class="form-control @error('estimated_cost') is-invalid @enderror"
                                        value="{{ old('estimated_cost', $externalTrainingRequest->estimated_cost) }}" step="0.01" min="0">
                                </div>
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Purpose / Justification</label>
                            <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                                rows="3">{{ old('purpose', $externalTrainingRequest->purpose) }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Document Upload Section -->
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="bi bi-file-earmark-arrow-up me-2"></i>Documents
                        </h6>
                        <p class="text-muted small mb-3">Leave blank to keep existing files. Upload new files to replace.</p>

                        <div class="row">
                            <!-- Request Form -->
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-text text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="mb-2">Request Form</h6>
                                        @if($externalTrainingRequest->request_form_path)
                                            <p class="small text-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Uploaded
                                            </p>
                                        @endif
                                        <input type="file" name="request_form" class="form-control form-control-sm @error('request_form') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        @error('request_form')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">PDF, JPG, PNG, DOC (max 10MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Department Head Letter -->
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-envelope-paper text-success mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="mb-2">Dept. Head Letter</h6>
                                        @if($externalTrainingRequest->department_head_letter_path)
                                            <p class="small text-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Uploaded
                                            </p>
                                        @endif
                                        <input type="file" name="department_head_letter" class="form-control form-control-sm @error('department_head_letter') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        @error('department_head_letter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">PDF, JPG, PNG, DOC (max 10MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Requesting Party Document -->
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-check text-warning mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="mb-2">Requesting Party</h6>
                                        @if($externalTrainingRequest->requesting_party_document_path)
                                            <p class="small text-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Uploaded
                                            </p>
                                        @endif
                                        <input type="file" name="requesting_party_document" class="form-control form-control-sm @error('requesting_party_document') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        @error('requesting_party_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">PDF, JPG, PNG, DOC (max 10MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
                            <a href="{{ route('my-external-training-requests') }}" class="btn btn-light px-4">
                                Cancel
                            </a>
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary px-4">
                                <i class="bi bi-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-2"></i>{{ $externalTrainingRequest->status === 'rejected' ? 'Resubmit Request' : 'Submit Request' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-person-badge text-primary me-2"></i>Requestor Information
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Name:</td>
                            <td class="fw-semibold">{{ $employee->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Employee #:</td>
                            <td>{{ $employee->employee_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Department:</td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-clock-history text-primary me-2"></i>Request History
                    </h6>
                    <div class="small">
                        <p class="mb-2">
                            <strong>Created:</strong><br>
                            {{ $externalTrainingRequest->created_at->format('M d, Y h:i A') }}
                        </p>
                        <p class="mb-2">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $externalTrainingRequest->status_badge }}">{{ $externalTrainingRequest->status_label }}</span>
                        </p>
                        @if($externalTrainingRequest->reviewed_at)
                            <p class="mb-0">
                                <strong>Reviewed:</strong><br>
                                {{ $externalTrainingRequest->reviewed_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });
});
</script>
@endpush
@endsection
