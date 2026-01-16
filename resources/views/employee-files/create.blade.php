@extends('layouts.app')

@section('title', 'Upload File - EHRMS')
@section('page-title', 'Upload Employee File')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employee-files.index', $employee) }}">{{ $employee->getFullNameAttribute() }} - 201 Files</a></li>
            <li class="breadcrumb-item active">Upload File</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-cloud-upload text-primary me-2"></i>Upload 201 File
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Employee Info -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Uploading for:</strong> {{ $employee->getFullNameAttribute() }}
                                <br>
                                <small class="text-muted">{{ $employee->employee_number }} - {{ $employee->position }}</small>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('employee-files.store', $employee) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">File Type <span class="text-danger">*</span></label>
                            <select name="file_type" class="form-select @error('file_type') is-invalid @enderror" required>
                                <option value="">Select file type</option>
                                <optgroup label="Essential Documents">
                                    <option value="pds" {{ old('file_type') == 'pds' ? 'selected' : '' }}>Personal Data Sheet (PDS)</option>
                                    <option value="service_record" {{ old('file_type') == 'service_record' ? 'selected' : '' }}>Service Record</option>
                                </optgroup>
                                <optgroup label="Educational">
                                    <option value="tor" {{ old('file_type') == 'tor' ? 'selected' : '' }}>Transcript of Records (TOR)</option>
                                    <option value="diploma" {{ old('file_type') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="certificate" {{ old('file_type') == 'certificate' ? 'selected' : '' }}>Certificates (Training/Seminar)</option>
                                </optgroup>
                                <optgroup label="Clearances & Medical">
                                    <option value="nbi_clearance" {{ old('file_type') == 'nbi_clearance' ? 'selected' : '' }}>NBI Clearance</option>
                                    <option value="medical_certificate" {{ old('file_type') == 'medical_certificate' ? 'selected' : '' }}>Medical Certificate</option>
                                </optgroup>
                                <optgroup label="Government IDs">
                                    <option value="tax_identification" {{ old('file_type') == 'tax_identification' ? 'selected' : '' }}>Tax Identification Number (TIN)</option>
                                </optgroup>
                                <optgroup label="Civil Documents">
                                    <option value="birth_certificate" {{ old('file_type') == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                                    <option value="marriage_certificate" {{ old('file_type') == 'marriage_certificate' ? 'selected' : '' }}>Marriage Certificate</option>
                                </optgroup>
                                <optgroup label="Others">
                                    <option value="other" {{ old('file_type') == 'other' ? 'selected' : '' }}>Other Documents</option>
                                </optgroup>
                            </select>
                            @error('file_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Allowed: PDF, JPG, PNG, DOC, DOCX (Max 10MB)</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description (Optional)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Add any notes or remarks about this file">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('employee-files.index', $employee) }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-cloud-upload me-2"></i>Upload File
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
                        <i class="bi bi-info-circle text-primary me-2"></i>File Upload Guidelines
                    </h6>
                    
                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Allowed File Types:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>PDF documents (.pdf)</li>
                            <li>Images (.jpg, .jpeg, .png)</li>
                            <li>Word documents (.doc, .docx)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>File Size Limit:</strong></small>
                        <p class="small text-muted mb-0">Maximum 10MB per file</p>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Best Practices:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Scan documents in high quality</li>
                            <li>Ensure text is readable</li>
                            <li>Use descriptive filenames</li>
                            <li>Add remarks for clarity</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning alert-sm mb-0">
                        <small><i class="bi bi-exclamation-triangle me-2"></i><strong>Note:</strong> Uploaded files will be stored securely and can only be accessed by HR staff and the employee.</small>
                    </div>
                </div>
            </div>

            <!-- Current Files Status -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-files text-primary me-2"></i>Current Files
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Files</span>
                        <strong>{{ $employee->files->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Total Size</span>
                        <strong>{{ number_format($employee->files->sum('file_size') / 1024 / 1024, 2) }} MB</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
