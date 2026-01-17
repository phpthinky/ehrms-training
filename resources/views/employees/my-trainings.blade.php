@extends('layouts.app')

@section('title', 'My Trainings - EHRMS')
@section('page-title', 'My Trainings')

@section('content')
<div class="container-fluid">
    @if(!$employee)
        <!-- No Employee Record -->
        <div class="card border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-person-x text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                <h5 class="mt-3 mb-2">No Employee Record Found</h5>
                <p class="text-muted mb-4">Your account is not linked to an employee record. Please contact HR.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    @else
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                    <i class="bi bi-mortarboard text-primary me-2"></i>My Training History
                </h4>
                <p class="text-muted mb-0">All trainings you've participated in</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-list-check text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Trainings</small>
                                <h4 class="mb-0" style="font-weight: 600;">{{ $trainings->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check-circle text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Attended</small>
                                <h4 class="mb-0" style="font-weight: 600;">{{ $trainings->where('attendance_status', 'attended')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-award text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Certificates</small>
                                <h4 class="mb-0" style="font-weight: 600;">{{ $trainings->where('certificate_uploaded', true)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-clock text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Pending</small>
                                <h4 class="mb-0" style="font-weight: 600;">{{ $trainings->where('attendance_status', 'pending')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trainings List -->
        <div class="card border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0" style="font-weight: 600;">Training History ({{ $trainings->count() }})</h6>
            </div>
            <div class="card-body p-0">
                @if($trainings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Training Details</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Type</th>
                                    <th>Attendance</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainings as $attendance)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $attendance->training->title ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $attendance->training->topic->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($attendance->training->start_date)
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($attendance->training->start_date)->format('M d, Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $attendance->training->venue ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($attendance->training->type === 'internal')
                                                <span class="badge bg-primary-subtle text-primary">Internal</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info">External</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'attended' => 'success',
                                                    'absent' => 'danger',
                                                    'pending' => 'warning'
                                                ];
                                                $color = $statusColors[$attendance->attendance_status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                                {{ ucfirst($attendance->attendance_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->certificate_uploaded && $attendance->employee_file_id)
                                                <a href="{{ route('employee-files.download', $attendance->employee_file_id) }}" class="btn btn-sm btn-success">
                                                    <i class="bi bi-download me-1"></i>Download
                                                </a>
                                            @else
                                                <span class="text-muted small">Not available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-mortarboard text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h5 class="mt-3 mb-2">No Training Records Yet</h5>
                        <p class="text-muted mb-0">You haven't been enrolled in any trainings yet.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
