@extends('layouts.app')

@section('title', 'Upload Document - EHRMS')
@section('page-title', 'Upload Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('hr-documents.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Back to Documents
                </a>
            </div>

            <!-- Upload Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-cloud-upload me-2"></i>Upload New Document
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('hr-documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Document Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required
                                   placeholder="Enter document title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category" 
                                    required>
                                <option value="">Select category...</option>
                                <option value="policy" {{ old('category') == 'policy' ? 'selected' : '' }}>Policy</option>
                                <option value="memo" {{ old('category') == 'memo' ? 'selected' : '' }}>Memorandum</option>
                                <option value="form" {{ old('category') == 'form' ? 'selected' : '' }}>Form</option>
                                <option value="guideline" {{ old('category') == 'guideline' ? 'selected' : '' }}>Guideline</option>
                                <option value="manual" {{ old('category') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="template" {{ old('category') == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="report" {{ old('category') == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="letter" {{ old('category') == 'letter' ? 'selected' : '' }}>Letter</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Brief description of the document (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                Document File <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                Accepted formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG | Max size: 20MB
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Effective Date -->
                        <div class="mb-3">
                            <label for="effective_date" class="form-label">Effective Date</label>
                            <input type="date" 
                                   class="form-control @error('effective_date') is-invalid @enderror" 
                                   id="effective_date" 
                                   name="effective_date" 
                                   value="{{ old('effective_date') }}">
                            <div class="form-text">Date when this document takes effect</div>
                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confidential Checkbox -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_confidential" 
                                       name="is_confidential" 
                                       value="1"
                                       {{ old('is_confidential') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_confidential">
                                    <i class="bi bi-lock-fill text-danger me-1"></i>
                                    Mark as Confidential
                                </label>
                                <div class="form-text">Confidential documents will be clearly labeled</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-2"></i>Upload Document
                            </button>
                            <a href="{{ route('hr-documents.index') }}" class="btn btn-light">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Important:</strong> Only HR staff can access these documents. All uploads are securely stored.
            </div>
        </div>
    </div>
</div>
@endsection
