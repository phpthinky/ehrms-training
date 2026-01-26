@extends('layouts.app')

@section('title', 'External Training Requests - EHRMS')
@section('page-title', 'External Training Requests')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Requests</p>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-2">
                            <i class="bi bi-inbox text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Pending Review</p>
                            <h3 class="mb-0 text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-2">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Approved</p>
                            <h3 class="mb-0 text-success">{{ $stats['approved'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-2">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Rejected</p>
                            <h3 class="mb-0 text-danger">{{ $stats['rejected'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded p-2">
                            <i class="bi bi-x-circle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Completed</p>
                            <h3 class="mb-0 text-info">{{ $stats['completed'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-2">
                            <i class="bi bi-trophy text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('external-training-requests.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Training title, provider, employee..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('external-training-requests.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card border-0">
        @if($requests->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 mb-2">No Requests Found</h5>
                <p class="text-muted">No external training requests match your filter criteria.</p>
            </div>
        @else
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Employee</th>
                                <th class="border-0">Training</th>
                                <th class="border-0">Schedule</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Documents</th>
                                <th class="border-0">Submitted</th>
                                <th class="border-0 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                 style="width: 36px; height: 36px; font-size: 0.8rem; font-weight: 600;">
                                                {{ strtoupper(substr($request->employee->first_name, 0, 1) . substr($request->employee->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $request->employee->full_name }}</div>
                                                <small class="text-muted">{{ $request->employee->department->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($request->training_title, 30) }}</div>
                                        <small class="text-muted">{{ $request->training_provider ?? 'No provider' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $request->start_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $request->duration_days }} day(s)</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status_badge }}">
                                            {{ $request->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($request->hasAllDocuments())
                                            <span class="text-success">
                                                <i class="bi bi-check-circle me-1"></i>Complete
                                            </span>
                                        @else
                                            <span class="text-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i>Incomplete
                                            </span>
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
                                            @if($request->canBeReviewed())
                                                <a href="{{ route('external-training-requests.review', $request) }}"
                                                   class="btn btn-sm btn-primary" title="Review">
                                                    <i class="bi bi-clipboard-check"></i>
                                                </a>
                                            @endif
                                            @if($request->status === 'approved')
                                                <form action="{{ route('external-training-requests.mark-completed', $request) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Mark this training as completed?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Completed">
                                                        <i class="bi bi-check2-all"></i>
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
        @endif
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
