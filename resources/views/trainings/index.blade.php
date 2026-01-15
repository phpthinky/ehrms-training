@extends('layouts.app')

@section('title', 'Trainings - EHRMS')
@section('page-title', 'Training Management')

@section('content')
<div class="container-fluid">
    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">Training Programs</h4>
            <p class="text-muted mb-0">Manage internal and external training sessions</p>
        </div>
        <a href="{{ route('trainings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Schedule Training
        </a>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all" type="button">
                All Trainings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#upcoming" type="button">
                Upcoming
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ongoing" type="button">
                Ongoing
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed" type="button">
                Completed
            </button>
        </li>
    </ul>

    <!-- Search & Filter -->
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('trainings.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search training title or facilitator" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="internal" {{ request('type') == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="external" {{ request('type') == 'external' ? 'selected' : '' }}>External</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Trainings List -->
    <div class="row g-4">
        @forelse($trainings as $training)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 h-100" style="transition: all 0.3s;">
                    <div class="card-body">
                        <!-- Type Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            @if($training->type === 'internal')
                                <span class="badge bg-primary-subtle text-primary">
                                    <i class="bi bi-building me-1"></i>Internal
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info">
                                    <i class="bi bi-globe me-1"></i>External
                                </span>
                            @endif
                            
                            <!-- Status Badge -->
                            @if($training->status === 'scheduled')
                                <span class="badge bg-warning-subtle text-warning">Scheduled</span>
                            @elseif($training->status === 'ongoing')
                                <span class="badge bg-success-subtle text-success">Ongoing</span>
                            @elseif($training->status === 'completed')
                                <span class="badge bg-secondary-subtle text-secondary">Completed</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                            @endif
                        </div>

                        <!-- Training Title -->
                        <h5 class="mb-2" style="font-weight: 600;">{{ $training->title }}</h5>
                        
                        <!-- Description -->
                        @if($training->description)
                            <p class="text-muted small mb-3">{{ Str::limit($training->description, 100) }}</p>
                        @endif

                        <!-- Details -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($training->start_date)->format('M d, Y') }}
                                    @if($training->end_date && $training->end_date != $training->start_date)
                                        - {{ \Carbon\Carbon::parse($training->end_date)->format('M d, Y') }}
                                    @endif
                                </small>
                            </div>
                            
                            @if($training->venue)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-geo-alt text-muted me-2"></i>
                                    <small class="text-muted">{{ $training->venue }}</small>
                                </div>
                            @endif

                            @if($training->facilitator)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <small class="text-muted">{{ $training->facilitator }}</small>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                <i class="bi bi-people text-muted me-1"></i>
                                <strong>{{ $training->attendances_count ?? 0 }}</strong>
                                <span class="text-muted small">Attendees</span>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('trainings.show', $training) }}" class="btn btn-outline-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('trainings.edit', $training) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $training->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $training->id }}" action="{{ route('trainings.destroy', $training) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-bookmark text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                        <h5 class="mt-3">No trainings found</h5>
                        <p class="text-muted">Get started by scheduling your first training</p>
                        <a href="{{ route('trainings.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Schedule Training
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($trainings->hasPages())
        <div class="mt-4">
            {{ $trainings->links() }}
        </div>
    @endif
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
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this training? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
