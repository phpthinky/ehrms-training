@extends('layouts.app')

@section('title', 'My External Training Requests - EHRMS')
@section('page-title', 'My External Training Requests')

@section('content')
<div class="container-fluid">
    <!-- Header Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">Track and manage your external training requests</p>
        </div>
        <a href="{{ route('external-training-requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Request
        </a>
    </div>

    <!-- Status Summary -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-3">
                    <div class="text-secondary mb-1">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                    <h4 class="mb-0">{{ $requests->where('status', 'draft')->count() }}</h4>
                    <small class="text-muted">Drafts</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0" style="background-color: rgba(251, 191, 36, 0.1);">
                <div class="card-body text-center py-3">
                    <div class="text-warning mb-1">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h4 class="mb-0">{{ $requests->where('status', 'pending')->count() }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0" style="background-color: rgba(16, 185, 129, 0.1);">
                <div class="card-body text-center py-3">
                    <div class="text-success mb-1">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h4 class="mb-0">{{ $requests->where('status', 'approved')->count() }}</h4>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0" style="background-color: rgba(239, 68, 68, 0.1);">
                <div class="card-body text-center py-3">
                    <div class="text-danger mb-1">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <h4 class="mb-0">{{ $requests->where('status', 'rejected')->count() }}</h4>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    @if($requests->isEmpty())
        <div class="card border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 mb-2">No External Training Requests</h5>
                <p class="text-muted mb-4">You haven't submitted any external training requests yet.</p>
                <a href="{{ route('external-training-requests.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Submit Your First Request
                </a>
            </div>
        </div>
    @else
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Training</th>
                                <th class="border-0">Schedule</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Submitted</th>
                                <th class="border-0 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $request->training_title }}</div>
                                        <small class="text-muted">
                                            {{ $request->training_provider ?? 'Provider not specified' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div>{{ $request->start_date->format('M d, Y') }}</div>
                                        @if($request->start_date != $request->end_date)
                                            <small class="text-muted">to {{ $request->end_date->format('M d, Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status_badge }}">
                                            {{ $request->status_label }}
                                        </span>
                                        @if($request->status === 'rejected' && $request->rejection_reason)
                                            <i class="bi bi-info-circle text-danger ms-1"
                                               data-bs-toggle="tooltip"
                                               title="{{ $request->rejection_reason }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('external-training-requests.show', $request) }}"
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($request->canBeEdited())
                                                <a href="{{ route('external-training-requests.edit', $request) }}"
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            @if($request->status === 'draft')
                                                <form action="{{ route('external-training-requests.destroy', $request) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this draft?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
