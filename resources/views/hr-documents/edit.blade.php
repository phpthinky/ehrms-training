@extends('layouts.app')

@section('title', 'Edit Document - EHRMS')
@section('page-title', 'Edit Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('hr-documents.show', $hrDocument) }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Back to Document
                </a>
            </div>

            <!-- Edit Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Document
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('hr-documents.update', $hrDocument) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Document Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $hrDocument->title) }}" 
                                   required>
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
                                <option value="policy" {{ old('category', $hrDocument->category) == 'policy' ? 'selected' : '' }}>Policy</option>
                                <option value="memo" {{ old('category', $hrDocument->category) == 'memo' ? 'selected' : '' }}>Memorandum</option>
                                <option value="form" {{ old('category', $hrDocument->category) == 'form' ? 'selected' : '' }}>Form</option>
                                <option value="guideline" {{ old('category', $hrDocument->category) == 'guideline' ? 'selected' : '' }}>Guideline</option>
                                <option value="manual" {{ old('category', $hrDocument->category) == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="template" {{ old('category', $hrDocument->category) == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="report" {{ old('category', $hrDocument->category) == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="letter" {{ old('category', $hrDocument->category) == 'letter' ? 'selected' : '' }}>Letter</option>
                                <option value="other" {{ old('category', $hrDocument->category) == 'other' ? 'selected' : '' }}>Other</option>
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
                                      rows="3">{{ old('description', $hrDocument->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current File Info -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="{{ $hrDocument->file_icon }} fs-3 me-3"></i>
                                <div>
                                    <strong>Current File:</strong> {{ $hrDocument->file_name }}<br>
                                    <small>{{ $hrDocument->formatted_size }} | Uploaded {{ $hrDocument->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Replace File (Optional) -->
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                Replace File (Optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                Leave empty to keep current file. Max size: 20MB
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
                                   value="{{ old('effective_date', $hrDocument->effective_date?->format('Y-m-d')) }}">
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
                                       {{ old('is_confidential', $hrDocument->is_confidential) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_confidential">
                                    <i class="bi bi-lock-fill text-danger me-1"></i>
                                    Mark as Confidential
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('hr-documents.show', $hrDocument) }}" class="btn btn-light">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
