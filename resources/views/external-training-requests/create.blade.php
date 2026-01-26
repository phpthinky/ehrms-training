@extends('layouts.app')

@section('title', 'Request External Training - EHRMS')
@section('page-title', 'Request External Training')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card border-0 mb-4">
                <div class="card-body text-center py-5" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05));">
                    <div class="mb-3">
                        <i class="bi bi-mortarboard text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        External Training Request
                    </h3>
                    <p class="text-muted mb-0">Submit a request to attend an external training program</p>
                </div>
            </div>

            <!-- Form -->
            <div class="card border-0">
                <div class="card-body p-4">
                    <form action="{{ route('external-training-requests.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
                        @csrf

                        <!-- Instructions -->
                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex">
                                <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>Instructions:</strong>
                                    <ul class="mb-0 mt-2 small">
                                        <li>Fill in all required training details</li>
                                        <li>Upload the 3 required documents (PDF, JPG, PNG, DOC - max 10MB each)</li>
                                        <li>You can save as draft and submit later</li>
                                        <li>HR will review and approve/reject your request</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

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
                                    value="{{ old('training_title') }}" placeholder="e.g., Advanced Leadership Workshop" required>
                                @error('training_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Training Description</label>
                                <textarea name="training_description" class="form-control @error('training_description') is-invalid @enderror"
                                    rows="3" placeholder="Brief description of the training program">{{ old('training_description') }}</textarea>
                                @error('training_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Training Provider</label>
                                <input type="text" name="training_provider" class="form-control @error('training_provider') is-invalid @enderror"
                                    value="{{ old('training_provider') }}" placeholder="e.g., CSC, DILG, Private Institution">
                                @error('training_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Venue</label>
                                <input type="text" name="training_venue" class="form-control @error('training_venue') is-invalid @enderror"
                                    value="{{ old('training_venue') }}" placeholder="e.g., Manila Hotel, Online/Virtual">
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
                                    value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estimated Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="estimated_cost" class="form-control @error('estimated_cost') is-invalid @enderror"
                                        value="{{ old('estimated_cost') }}" placeholder="0.00" step="0.01" min="0">
                                </div>
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Purpose / Justification</label>
                            <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                                rows="3" placeholder="Why do you need to attend this training? How will it benefit your work?">{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Document Upload Section -->
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="bi bi-file-earmark-arrow-up me-2"></i>Required Documents
                        </h6>

                        <div class="row">
                            <!-- Request Form -->
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-text text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="mb-2">Request Form <span class="text-danger">*</span></h6>
                                        <p class="small text-muted mb-3">Training request form</p>
                                        <input type="file" name="request_form" id="request_form"
                                            class="form-control form-control-sm @error('request_form') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
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
                                        <h6 class="mb-2">Dept. Head Letter <span class="text-danger">*</span></h6>
                                        <p class="small text-muted mb-3">Endorsement from department head</p>
                                        <input type="file" name="department_head_letter" id="department_head_letter"
                                            class="form-control form-control-sm @error('department_head_letter') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
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
                                        <h6 class="mb-2">Requesting Party <span class="text-danger">*</span></h6>
                                        <p class="small text-muted mb-3">Document from requesting party</p>
                                        <input type="file" name="requesting_party_document" id="requesting_party_document"
                                            class="form-control form-control-sm @error('requesting_party_document') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
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
                                <i class="bi bi-send me-2"></i>Submit Request
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
                        <tr>
                            <td class="text-muted">Position:</td>
                            <td>{{ $employee->position ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips
                    </h6>
                    <div class="small text-muted">
                        <p class="mb-2"><strong>Be Specific:</strong> Provide complete training details</p>
                        <p class="mb-2"><strong>Documents:</strong> Ensure all 3 documents are ready before submitting</p>
                        <p class="mb-2"><strong>Draft:</strong> Save as draft if documents are not yet complete</p>
                        <p class="mb-0"><strong>Review:</strong> HR will review within 3-5 business days</p>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning mt-3 mb-0">
                <small>
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> All 3 documents are required before you can submit the request.
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate end date is after start date
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    const maxSize = 10 * 1024 * 1024; // 10MB

    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > maxSize) {
                alert('File size exceeds 10MB limit. Please choose a smaller file.');
                this.value = '';
            }
        });
    });
});
</script>
@endpush
@endsection
