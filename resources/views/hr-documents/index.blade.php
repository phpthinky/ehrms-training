@extends('layouts.app')

@section('title', 'HR Documents - EHRMS')
@section('page-title', 'HR Documents')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                <i class="bi bi-shield-lock text-primary me-2"></i>HR Documents
            </h4>
            <p class="text-muted mb-0">Secure document repository - HR Admin access only</p>
        </div>
        @if(auth()->user()->isStaff())
            <a href="{{ route('hr-documents.create') }}" class="btn btn-primary">
                <i class="bi bi-cloud-upload me-2"></i>Upload Document
            </a>
        @endif
    </div>

    <!-- Security Notice -->
    <div class="alert alert-info border-0 mb-4" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05));">
        <div class="d-flex align-items-start">
            <i class="bi bi-shield-check text-primary me-3" style="font-size: 1.5rem;"></i>
            <div>
                <strong class="d-block mb-1">Secure Document Storage</strong>
                <small class="text-muted">
                    @if(auth()->user()->isStaff())
                        As HR Staff, you can upload and manage confidential HR documents. All documents are accessible only to authorized personnel.
                    @else
                        These documents are for reference only. Only HR Staff can upload or modify documents.
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <button class="nav-link active" data-filter="all" onclick="filterDocuments('all')">
                All Documents
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-filter="policy" onclick="filterDocuments('policy')">
                Policies
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-filter="memo" onclick="filterDocuments('memo')">
                Memos
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-filter="form" onclick="filterDocuments('form')">
                Forms
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-filter="training" onclick="filterDocuments('training')">
                Training Materials
            </button>
        </li>
    </ul>

    <!-- Documents Grid -->
    <div class="row g-4">
        @forelse($documents ?? [] as $document)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card border-0 h-100" style="transition: all 0.3s;">
                    <div class="card-body">
                        <!-- File Icon -->
                        <div class="text-center mb-3">
                            <div class="file-icon mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1e40af); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                                @php
                                    $extension = strtolower(pathinfo($document->file_name ?? 'file.pdf', PATHINFO_EXTENSION));
                                @endphp
                                @if($extension === 'pdf')
                                    <i class="bi bi-file-pdf text-white" style="font-size: 2.5rem;"></i>
                                @elseif(in_array($extension, ['doc', 'docx']))
                                    <i class="bi bi-file-word text-white" style="font-size: 2.5rem;"></i>
                                @elseif(in_array($extension, ['xls', 'xlsx']))
                                    <i class="bi bi-file-excel text-white" style="font-size: 2.5rem;"></i>
                                @else
                                    <i class="bi bi-file-earmark text-white" style="font-size: 2.5rem;"></i>
                                @endif
                            </div>
                        </div>

                        <!-- File Info -->
                        <h6 class="mb-2 text-truncate" title="{{ $document->title ?? 'Document' }}">
                            {{ $document->title ?? 'Document' }}
                        </h6>
                        <p class="small text-muted mb-3">
                            @if($document->category ?? false)
                                <span class="badge bg-primary-subtle text-primary">{{ ucfirst($document->category) }}</span>
                            @endif
                        </p>

                        @if($document->description ?? false)
                            <p class="small text-muted mb-3">{{ Str::limit($document->description, 60) }}</p>
                        @endif

                        <!-- File Details -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ isset($document->created_at) ? \Carbon\Carbon::parse($document->created_at)->format('M d, Y') : 'N/A' }}
                            </small>
                            <small class="text-muted">
                                {{ $document->file_size ?? '0 KB' }}
                            </small>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                            @if(auth()->user()->isStaff())
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('hr-documents.edit', $document->id ?? 1) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $document->id ?? 1 }})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-lock" style="font-size: 5rem; opacity: 0.2;"></i>
                        </div>
                        <h5 class="mb-2">No Documents Yet</h5>
                        <p class="text-muted mb-4">
                            @if(auth()->user()->isStaff())
                                Start building your secure document library by uploading your first HR document.
                            @else
                                HR documents will appear here once uploaded by HR Staff.
                            @endif
                        </p>
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('hr-documents.create') }}" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-2"></i>Upload First Document
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
function filterDocuments(type) {
    // Update active tab
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-filter="${type}"]`).classList.add('active');
    
    // Filter logic (reload with parameter)
    if (type !== 'all') {
        window.location.href = "{{ route('hr-documents.index') }}?category=" + type;
    } else {
        window.location.href = "{{ route('hr-documents.index') }}";
    }
}

function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
