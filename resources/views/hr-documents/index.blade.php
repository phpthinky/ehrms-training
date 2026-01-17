@extends('layouts.app')

@section('title', 'HR Documents - EHRMS')
@section('page-title', 'HR Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Confidential HR Documents</h4>
                    <p class="text-muted mb-0">Manage policies, memos, forms, and other HR documents</p>
                </div>
                <a href="{{ route('hr-documents.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Upload Document
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary bg-opacity-10 rounded p-3">
                                <i class="bi bi-files fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Documents</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-info bg-opacity-10 rounded p-3">
                                <i class="bi bi-shield-check fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Policies</h6>
                            <h3 class="mb-0">{{ $stats['policies'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-warning bg-opacity-10 rounded p-3">
                                <i class="bi bi-envelope-paper fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Memos</h6>
                            <h3 class="mb-0">{{ $stats['memos'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-success bg-opacity-10 rounded p-3">
                                <i class="bi bi-file-earmark-text fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Forms</h6>
                            <h3 class="mb-0">{{ $stats['forms'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">All Documents</h5>
        </div>
        <div class="card-body p-0">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%"><i class="bi bi-file-earmark"></i></th>
                                <th width="35%">Document Title</th>
                                <th width="15%">Category</th>
                                <th width="10%">File Size</th>
                                <th width="15%">Uploaded By</th>
                                <th width="10%">Date</th>
                                <th width="10%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td class="text-center">
                                        <i class="{{ $document->file_icon }} fs-4"></i>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $document->title }}</div>
                                        @if($document->is_confidential)
                                            <span class="badge badge-sm bg-danger-subtle text-danger mt-1">
                                                <i class="bi bi-lock-fill"></i> Confidential
                                            </span>
                                        @endif
                                        @if($document->description)
                                            <div class="text-muted small mt-1">{{ Str::limit($document->description, 60) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $document->category_color }}-subtle text-{{ $document->category_color }}">
                                            {{ $document->category_label }}
                                        </span>
                                    </td>
                                    <td>{{ $document->formatted_size }}</td>
                                    <td>{{ $document->uploader->name ?? 'Unknown' }}</td>
                                    <td>{{ $document->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('hr-documents.show', $document) }}" 
                                               class="btn btn-light" 
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('hr-documents.download', $document) }}" 
                                               class="btn btn-light" 
                                               title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="{{ route('hr-documents.edit', $document) }}" 
                                               class="btn btn-light" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('hr-documents.destroy', $document) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this document?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">No documents uploaded yet</p>
                    <a href="{{ route('hr-documents.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Upload First Document
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
