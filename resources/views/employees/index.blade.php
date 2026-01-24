@extends('layouts.app')

@section('title', 'Employees - EHRMS')
@section('page-title', 'Employee Management')

@section('content')
<div class="container-fluid">
    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">Employees</h4>
            <p class="text-muted mb-0">Manage employee records and information</p>
        </div>
        @if(auth()->user()->isHRAdmin())
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Employee
            </a>
        @endif
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or employee number" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach(\App\Models\Department::where('is_active', true)->get() as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Employment Type</label>
                    <select name="employment_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="permanent" {{ request('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="job_order" {{ request('employment_type') == 'job_order' ? 'selected' : '' }}>Job Order</option>
                        <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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

    <!-- Employees Table -->
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="px-4 py-3 fw-semibold" style="color: #475569;">Employee #</th>
                            <th class="py-3 fw-semibold" style="color: #475569;">Name</th>
                            <th class="py-3 fw-semibold" style="color: #475569;">Department</th>
                            <th class="py-3 fw-semibold" style="color: #475569;">Position</th>
                            <th class="py-3 fw-semibold" style="color: #475569;">Employment Type</th>
                            <th class="py-3 fw-semibold" style="color: #475569;">Status</th>
                            <th class="py-3 fw-semibold text-end px-4" style="color: #475569;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border">{{ $employee->employee_number }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3" style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $employee->getFullNameAttribute() }}</div>
                                            <small class="text-muted">{{ $employee->user->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-muted">{{ $employee->department->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3">{{ $employee->position }}</td>
                                <td class="py-3">
                                    @if($employee->employment_type === 'permanent')
                                        <span class="badge bg-success-subtle text-success">Permanent</span>
                                    @elseif($employee->employment_type === 'job_order')
                                        <span class="badge bg-warning-subtle text-warning">Job Order</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info">Contract</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($employee->status === 'active')
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-end px-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $employee->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-0">No employees found</p>
                                        <small>Try adjusting your search filters</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($employees->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
                    </div>
                    {{ $employees->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
