@extends('layouts.app')

@section('title', 'Training Details - EHRMS')
@section('page-title', 'Training Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Trainings</a></li>
            <li class="breadcrumb-item active">{{ $training->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Training Details -->
        <div class="col-lg-8">
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            {{ $training->title }}
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('trainings.edit', $training) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Status and Type -->
                    <div class="mb-4">
                        @if($training->type === 'internal')
                            <span class="badge bg-primary-subtle text-primary me-2">
                                <i class="bi bi-building me-1"></i>Internal Training
                            </span>
                        @else
                            <span class="badge bg-info-subtle text-info me-2">
                                <i class="bi bi-globe me-1"></i>External Training
                            </span>
                        @endif

                        @if($training->status === 'scheduled')
                            <span class="badge bg-warning-subtle text-warning">Scheduled</span>
                        @elseif($training->status === 'ongoing')
                            <span class="badge bg-success-subtle text-success">Ongoing</span>
                        @elseif($training->status === 'completed')
                            <span class="badge bg-secondary-subtle text-secondary">Completed</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                        @endif

                        @if($training->rank_level !== 'all')
                            <span class="badge bg-light text-dark border ms-2">
                                {{ ucfirst($training->rank_level) }} Rank Only
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($training->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="mb-0">{{ $training->description }}</p>
                        </div>
                    @endif

                    <!-- Details Grid -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-calendar3 me-1"></i>Date
                                </small>
                                <strong>
                                    {{ \Carbon\Carbon::parse($training->start_date)->format('F d, Y') }}
                                    @if($training->end_date && $training->end_date != $training->start_date)
                                        - {{ \Carbon\Carbon::parse($training->end_date)->format('F d, Y') }}
                                    @endif
                                </strong>
                            </div>
                        </div>

                        @if($training->start_time || $training->end_time)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-clock me-1"></i>Time
                                    </small>
                                    <strong>
                                        {{ $training->start_time ? \Carbon\Carbon::parse($training->start_time)->format('g:i A') : '' }}
                                        @if($training->end_time)
                                            - {{ \Carbon\Carbon::parse($training->end_time)->format('g:i A') }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        @endif

                        @if($training->venue)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-geo-alt me-1"></i>Venue
                                    </small>
                                    <strong>{{ $training->venue }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($training->facilitator)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-person me-1"></i>Facilitator
                                    </small>
                                    <strong>{{ $training->facilitator }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($training->topic)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-tag me-1"></i>Topic
                                    </small>
                                    <strong>{{ $training->topic->title }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Additional Notes -->
                    @if($training->notes)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Additional Notes</h6>
                            <p class="mb-0">{{ $training->notes }}</p>
                        </div>
                    @endif

                    <!-- Created By -->
                    <div class="pt-3 border-top">
                        <small class="text-muted">
                            Created by <strong>{{ $training->creator->name }}</strong> 
                            on {{ \Carbon\Carbon::parse($training->created_at)->format('F d, Y') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            <i class="bi bi-people me-2"></i>Attendees ({{ $training->attendances->count() }})
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendeeModal">
                            <i class="bi bi-plus-circle me-1"></i>Add Attendee
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($training->attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8fafc;">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold" style="color: #475569;">Employee</th>
                                        <th class="py-3 fw-semibold" style="color: #475569;">Department</th>
                                        <th class="py-3 fw-semibold" style="color: #475569;">Attendance</th>
                                        <th class="py-3 fw-semibold" style="color: #475569;">Certificate</th>
                                        <th class="py-3 fw-semibold text-end px-4" style="color: #475569;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($training->attendances as $attendance)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2" style="width: 35px; height: 35px; border-radius: 8px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($attendance->employee->first_name, 0, 1) . substr($attendance->employee->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $attendance->employee->getFullNameAttribute() }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $attendance->employee->employee_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="text-muted">{{ $attendance->employee->department->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-3">
                                                @if($attendance->attendance_status === 'attended')
                                                    <span class="badge bg-success-subtle text-success">Attended</span>
                                                @elseif($attendance->attendance_status === 'absent')
                                                    <span class="badge bg-danger-subtle text-danger">Absent</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                @if($attendance->certificate_uploaded)
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="bi bi-award me-1"></i>Uploaded
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-end px-4">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Update Status" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $attendance->id }}">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                    @if(!$attendance->certificate_uploaded)
                                                        <button type="button" class="btn btn-outline-success" title="Upload Certificate" data-bs-toggle="modal" data-bs-target="#uploadCertModal{{ $attendance->id }}">
                                                            <i class="bi bi-upload"></i>
                                                        </button>
                                                    @else
                                                        <a href="{{ route('trainings.attendance.download-certificate', $attendance) }}" class="btn btn-outline-info" title="Download Certificate">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('trainings.attendance.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this attendee?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Remove">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateStatusModal{{ $attendance->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Update Attendance</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('trainings.attendance.update-status', $attendance) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label small">Status</label>
                                                                <select name="attendance_status" class="form-select form-select-sm" required>
                                                                    <option value="registered" {{ $attendance->attendance_status === 'registered' ? 'selected' : '' }}>Registered</option>
                                                                    <option value="attended" {{ $attendance->attendance_status === 'attended' ? 'selected' : '' }}>Attended</option>
                                                                    <option value="absent" {{ $attendance->attendance_status === 'absent' ? 'selected' : '' }}>Absent</option>
                                                                    <option value="excused" {{ $attendance->attendance_status === 'excused' ? 'selected' : '' }}>Excused</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small">Remarks (Optional)</label>
                                                                <textarea name="remarks" class="form-control form-control-sm" rows="2">{{ $attendance->remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Certificate Modal -->
                                        @if(!$attendance->certificate_uploaded)
                                        <div class="modal fade" id="uploadCertModal{{ $attendance->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Upload Certificate</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('trainings.attendance.upload-certificate', $attendance) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p class="small text-muted mb-3">
                                                                <strong>Employee:</strong> {{ $attendance->employee->getFullNameAttribute() }}
                                                            </p>
                                                            <div class="mb-3">
                                                                <label class="form-label small">Certificate File <span class="text-danger">*</span></label>
                                                                <input type="file" name="certificate" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                                                                <small class="text-muted">PDF, JPG, PNG (Max 5MB)</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small">Certificate Number</label>
                                                                <input type="text" name="certificate_number" class="form-control form-control-sm" placeholder="e.g., CERT-2024-001">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small">Certificate Date</label>
                                                                <input type="date" name="certificate_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-upload me-1"></i>Upload
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.2;"></i>
                            <p class="mt-3 text-muted">No attendees yet</p>
                            <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addAttendeeModal">
                                <i class="bi bi-plus-circle me-1"></i>Add First Attendee
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>Statistics
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Attendees</span>
                        <strong>{{ $training->attendances->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Attended</span>
                        <strong class="text-success">{{ $training->attendances->where('attendance_status', 'attended')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Absent</span>
                        <strong class="text-danger">{{ $training->attendances->where('attendance_status', 'absent')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Certificates</span>
                        <strong class="text-primary">{{ $training->attendances->where('certificate_uploaded', true)->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAttendeeModal">
                            <i class="bi bi-person-plus me-2"></i>Add Attendee
                        </button>
                        
                        @if($training->attendances->count() > 0)
                            <form action="{{ route('trainings.attendance.mark-all-attended', $training) }}" method="POST" onsubmit="return confirm('Mark all attendees as attended?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                    <i class="bi bi-check-all me-2"></i>Mark All Attended
                                </button>
                            </form>
                            
                            <form action="{{ route('trainings.attendance.notify', $training) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm w-100">
                                    <i class="bi bi-bell me-2"></i>Send Notifications
                                </button>
                            </form>
                        @endif
                        
                        @if($training->status === 'scheduled')
                            <hr class="my-2">
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="bi bi-play-circle me-2"></i>Start Training
                                </button>
                            </form>
                        @endif
                        
                        @if($training->status === 'ongoing')
                            <hr class="my-2">
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-check-circle me-2"></i>Complete Training
                                </button>
                            </form>
                        @endif
                        
                        @if($training->attendances->count() > 0)
                            <hr class="my-2">
                            <a href="{{ route('trainings.attendance.export', $training) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-download me-2"></i>Export Attendance
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Training Info -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Training Info
                    </h6>
                    <small class="text-muted d-block mb-2">
                        <strong>Training ID:</strong> #{{ $training->id }}
                    </small>
                    <small class="text-muted d-block mb-2">
                        <strong>Created:</strong> {{ $training->created_at->format('M d, Y') }}
                    </small>
                    <small class="text-muted d-block">
                        <strong>Last Updated:</strong> {{ $training->updated_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Attendee Modal -->
<div class="modal fade" id="addAttendeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Attendee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('trainings.attendance.add', $training) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required id="employeeSelect">
                            <option value="">Choose employee...</option>
                            @php
                                $eligibleEmployees = \App\Models\Employee::where('status', 'active')
                                    ->when($training->rank_level !== 'all', function($q) use ($training) {
                                        $q->where('rank_level', $training->rank_level);
                                    })
                                    ->whereNotIn('id', $training->attendances->pluck('employee_id'))
                                    ->with('department')
                                    ->orderBy('last_name')
                                    ->get();
                            @endphp
                            @foreach($eligibleEmployees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->getFullNameAttribute() }} - {{ $emp->employee_number }}
                                    ({{ $emp->department->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @if($eligibleEmployees->count() === 0)
                            <small class="text-muted">No eligible employees available to add.</small>
                        @endif
                    </div>
                    @if($training->rank_level !== 'all')
                        <div class="alert alert-info alert-sm">
                            <small><i class="bi bi-info-circle me-2"></i>Only showing <strong>{{ ucfirst($training->rank_level) }}</strong> rank employees.</small>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" {{ $eligibleEmployees->count() === 0 ? 'disabled' : '' }}>
                        <i class="bi bi-plus-circle me-1"></i>Add Attendee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="delete-form" action="{{ route('trainings.destroy', $training) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this training? This will also remove all attendance records.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection
