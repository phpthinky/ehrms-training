@extends('layouts.app')

@section('title', 'My Trainings - EHRMS')
@section('page-title', 'My Trainings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">My Training History</h4>
                    <p class="text-muted mb-0">View all your training attendance and certificates</p>
                </div>
                <a href="{{ route('training-survey') }}" class="btn btn-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Training Survey
                </a>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-filter="all" onclick="filterTrainings('all')">
                        All Trainings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-filter="upcoming" onclick="filterTrainings('upcoming')">
                        Upcoming
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-filter="attended" onclick="filterTrainings('attended')">
                        Attended
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-filter="certificates" onclick="filterTrainings('certificates')">
                        With Certificate
                    </button>
                </li>
            </ul>

            <!-- Trainings List -->
            @if($trainings->count() > 0)
                <div class="row g-4">
                    @foreach($trainings as $attendance)
                        @php $training = $attendance->training; @endphp
                        <div class="col-12">
                            <div class="card border-0 h-100" style="transition: all 0.3s;">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Training Info -->
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-2" style="font-weight: 600;">{{ $training->title }}</h5>
                                                    
                                                    <!-- Badges -->
                                                    <div class="mb-2">
                                                        @if($training->type === 'internal')
                                                            <span class="badge bg-primary-subtle text-primary me-1">
                                                                <i class="bi bi-building me-1"></i>Internal
                                                            </span>
                                                        @else
                                                            <span class="badge bg-info-subtle text-info me-1">
                                                                <i class="bi bi-globe me-1"></i>External
                                                            </span>
                                                        @endif

                                                        @if($attendance->attendance_status === 'attended')
                                                            <span class="badge bg-success-subtle text-success me-1">
                                                                <i class="bi bi-check-circle me-1"></i>Attended
                                                            </span>
                                                        @elseif($attendance->attendance_status === 'registered')
                                                            <span class="badge bg-warning-subtle text-warning me-1">
                                                                <i class="bi bi-clock me-1"></i>Upcoming
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary-subtle text-secondary me-1">
                                                                {{ ucfirst($attendance->attendance_status) }}
                                                            </span>
                                                        @endif

                                                        @if($attendance->certificate_uploaded)
                                                            <span class="badge bg-primary-subtle text-primary">
                                                                <i class="bi bi-award me-1"></i>Certificate
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            @if($training->description)
                                                <p class="text-muted mb-3">{{ Str::limit($training->description, 150) }}</p>
                                            @endif

                                            <!-- Details -->
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <strong>Date:</strong>
                                                        {{ \Carbon\Carbon::parse($training->start_date)->format('M d, Y') }}
                                                        @if($training->end_date && $training->end_date != $training->start_date)
                                                            - {{ \Carbon\Carbon::parse($training->end_date)->format('M d, Y') }}
                                                        @endif
                                                    </small>
                                                </div>

                                                @if($training->venue)
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            <strong>Venue:</strong> {{ $training->venue }}
                                                        </small>
                                                    </div>
                                                @endif

                                                @if($training->facilitator)
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <i class="bi bi-person me-1"></i>
                                                            <strong>Facilitator:</strong> {{ $training->facilitator }}
                                                        </small>
                                                    </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        <strong>Registered:</strong> {{ $attendance->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-4 d-flex flex-column justify-content-between align-items-end">
                                            <!-- Certificate Status -->
                                            <div class="text-end mb-3">
                                                @if($attendance->certificate_uploaded)
                                                    <div class="mb-2">
                                                        <i class="bi bi-award text-primary" style="font-size: 2rem;"></i>
                                                    </div>
                                                    @if($attendance->certificate_file_id)
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-download me-1"></i>Download Certificate
                                                        </button>
                                                    @endif
                                                @else
                                                    @if($attendance->attendance_status === 'attended')
                                                        <small class="text-muted d-block mb-2">Certificate pending</small>
                                                        <button class="btn btn-sm btn-outline-secondary" onclick="alert('Certificate upload feature coming soon')">
                                                            <i class="bi bi-upload me-1"></i>Upload Certificate
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- Training Status -->
                                            <div class="text-end">
                                                @if($training->status === 'completed')
                                                    <small class="text-muted">Training completed</small>
                                                @elseif($training->status === 'ongoing')
                                                    <small class="text-success fw-semibold">Training in progress</small>
                                                @elseif($training->status === 'scheduled')
                                                    <small class="text-primary fw-semibold">
                                                        Starts in {{ \Carbon\Carbon::parse($training->start_date)->diffForHumans() }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($trainings->hasPages())
                    <div class="mt-4">
                        {{ $trainings->links() }}
                    </div>
                @endif
            @else
                <div class="card border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                        <h5 class="mt-3">No trainings yet</h5>
                        <p class="text-muted">You haven't been registered for any trainings.</p>
                        <small class="text-muted">Contact HR if you need to register for trainings.</small>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Statistics -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>My Statistics
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Trainings</span>
                        <strong>{{ $trainings->total() ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Attended</span>
                        <strong class="text-success">
                            {{ auth()->user()->employee->trainings->where('attendance_status', 'attended')->count() }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Upcoming</span>
                        <strong class="text-warning">
                            {{ auth()->user()->employee->trainings->where('attendance_status', 'registered')->count() }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Certificates</span>
                        <strong class="text-primary">
                            {{ auth()->user()->employee->trainings->where('certificate_uploaded', true)->count() }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Training Hours -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-clock text-primary me-2"></i>Training Hours
                    </h6>
                    <div class="text-center">
                        <h2 class="mb-0 text-primary" style="font-weight: 700;">
                            {{ auth()->user()->employee->trainings->where('attendance_status', 'attended')->count() * 8 }}
                        </h2>
                        <small class="text-muted">Hours this year</small>
                    </div>
                    <hr class="my-3">
                    <small class="text-muted d-block text-center">
                        Based on 8 hours per training
                    </small>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('training-survey') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-clipboard-check me-2"></i>Training Survey
                        </a>
                        <a href="{{ route('my-files') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-folder me-2"></i>My 201 Files
                        </a>
                        <a href="{{ route('my-profile') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-person me-2"></i>My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterTrainings(type) {
    // Update active tab
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-filter="${type}"]`).classList.add('active');
    
    // Reload with filter
    if (type !== 'all') {
        window.location.href = "{{ route('my-trainings') }}?filter=" + type;
    } else {
        window.location.href = "{{ route('my-trainings') }}";
    }
}
</script>
@endpush
@endsection
