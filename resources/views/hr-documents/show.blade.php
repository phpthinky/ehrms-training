@extends('layouts.app')

@section('title', 'Document Details - EHRMS')
@section('page-title', 'Document Details')

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

            <!-- Document Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Document Information
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('hr-documents.download', $hrDocument) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                            <a href="{{ route('hr-documents.edit', $hrDocument) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Document Title -->
                    <div class="mb-4">
                        <h3 class="mb-2">{{ $hrDocument->title }}</h3>
                        <div class="d-flex gap-2">
                            <span class="badge bg-{{ $hrDocument->category_color }}-subtle text-{{ $hrDocument->category_color }}">
                                {{ $hrDocument->category_label }}
                            </span>
                            @if($hrDocument->is_confidential)
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="bi bi-lock-fill"></i> Confidential
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($hrDocument->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="mb-0">{{ $hrDocument->description }}</p>
                        </div>
                    @endif

                    <!-- File Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="bi bi-file-earmark me-1"></i>File Information
                                    </h6>
                                    <div class="mb-2">
                                        <small class="text-muted">File Name:</small>
                                        <div class="fw-medium">{{ $hrDocument->file_name }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">File Type:</small>
                                        <div class="fw-medium text-uppercase">{{ $hrDocument->file_type }}</div>
                                    </div>
                                    <div>
                                        <small class="text-muted">File Size:</small>
                                        <div class="fw-medium">{{ $hrDocument->formatted_size }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="bi bi-calendar me-1"></i>Dates
                                    </h6>
                                    @if($hrDocument->effective_date)
                                        <div class="mb-2">
                                            <small class="text-muted">Effective Date:</small>
                                            <div class="fw-medium">{{ $hrDocument->effective_date->format('F d, Y') }}</div>
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <small class="text-muted">Uploaded:</small>
                                        <div class="fw-medium">{{ $hrDocument->created_at->format('F d, Y g:i A') }}</div>
                                    </div>
                                    <div>
                                        <small class="text-muted">Last Modified:</small>
                                        <div class="fw-medium">{{ $hrDocument->updated_at->format('F d, Y g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploader Information -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-person me-1"></i>Uploaded By
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary text-white me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 600;">
                                {{ substr($hrDocument->uploader->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-medium">{{ $hrDocument->uploader->name ?? 'Unknown' }}</div>
                                <small class="text-muted">{{ $hrDocument->uploader->email ?? '' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-eye me-1"></i>File Preview
                        </h6>
                        <div class="text-center p-4 bg-light rounded">
                            <i class="{{ $hrDocument->file_icon }}" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-2 mb-0">{{ $hrDocument->file_name }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('hr-documents.download', $hrDocument) }}" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i>Download Document
                        </a>
                        <a href="{{ route('hr-documents.edit', $hrDocument) }}" class="btn btn-secondary">
                            <i class="bi bi-pencil me-2"></i>Edit Details
                        </a>
                        <form action="{{ route('hr-documents.destroy', $hrDocument) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
