@extends('layouts.app')

@section('title', 'Departments - EHRMS')
@section('page-title', 'Department Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">Departments</h4>
            <p class="text-muted mb-0">Manage organizational departments</p>
        </div>
        @if(auth()->user()->isHRAdmin())
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Department
            </a>
        @endif
    </div>

    <div class="row g-4">
        @forelse($departments as $department)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 h-100" style="transition: all 0.3s;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-diagram-3 text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('departments.show', $department) }}">
                                        <i class="bi bi-eye me-2"></i>View
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('departments.edit', $department) }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h5 class="mb-2" style="font-weight: 600;">{{ $department->name }}</h5>
                        <p class="text-muted small mb-3">Code: <strong>{{ $department->code }}</strong></p>
                        
                        @if($department->description)
                            <p class="text-muted small">{{ Str::limit($department->description, 100) }}</p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div>
                                <i class="bi bi-people text-muted me-1"></i>
                                <strong>{{ $department->employees_count }}</strong>
                                <span class="text-muted small">Employees</span>
                            </div>
                            @if($department->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-diagram-3-fill text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                        <h5 class="mt-3">No departments yet</h5>
                        <p class="text-muted">Get started by creating your first department</p>
                        <a href="{{ route('departments.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Add Department
                        </a>
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
@endsection
