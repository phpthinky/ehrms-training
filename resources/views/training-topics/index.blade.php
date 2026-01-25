@extends('layouts.app')

@section('title', 'Training Topics - EHRMS')
@section('page-title', 'Training Topics')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Training Topics</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 700;">
                <i class="bi bi-bookmarks text-primary me-2"></i>Training Topics
            </h4>
            <p class="text-muted mb-0">Manage training subject categories and topics</p>
        </div>
        <a href="{{ route('training-topics.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Topic
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-primary-subtle">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0 text-primary">{{ $topics->total() }}</h3>
                            <small class="text-muted">Total Topics</small>
                        </div>
                        <i class="bi bi-bookmarks text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-success-subtle">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0 text-success">{{ $topics->where('is_active', true)->count() }}</h3>
                            <small class="text-muted">Active Topics</small>
                        </div>
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-warning-subtle">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0 text-warning">{{ $topics->where('category', 'technical')->count() }}</h3>
                            <small class="text-muted">Technical Topics</small>
                        </div>
                        <i class="bi bi-cpu text-warning" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-info-subtle">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0 text-info">{{ $topics->where('category', 'leadership')->count() }}</h3>
                            <small class="text-muted">Leadership Topics</small>
                        </div>
                        <i class="bi bi-award text-info" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topics List -->
    <div class="card border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0">All Training Topics</h6>
        </div>
        <div class="card-body p-0">
            @if($topics->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Topic</th>
                                <th>Category</th>
                                <th>Rank Level</th>
                                <th>Trainings</th>
                                <th>Status</th>
                                <th class="text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topics as $topic)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3" style="width: 40px; height: 40px; border-radius: 8px; background:
                                                @if($topic->category === 'technical') #dbeafe
                                                @elseif($topic->category === 'leadership') #fef3c7
                                                @elseif($topic->category === 'compliance') #fecaca
                                                @elseif($topic->category === 'soft_skills') #d1fae5
                                                @else #f3f4f6
                                                @endif
                                                ; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi
                                                    @if($topic->category === 'technical') bi-cpu
                                                    @elseif($topic->category === 'leadership') bi-award
                                                    @elseif($topic->category === 'compliance') bi-shield-check
                                                    @elseif($topic->category === 'soft_skills') bi-people
                                                    @else bi-bookmark
                                                    @endif
                                                    " style="font-size: 1.2rem; color:
                                                    @if($topic->category === 'technical') #3b82f6
                                                    @elseif($topic->category === 'leadership') #f59e0b
                                                    @elseif($topic->category === 'compliance') #ef4444
                                                    @elseif($topic->category === 'soft_skills') #10b981
                                                    @else #6b7280
                                                    @endif
                                                    ;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $topic->title }}</div>
                                                @if($topic->description)
                                                    <small class="text-muted">{{ Str::limit($topic->description, 60) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($topic->category === 'technical') bg-primary-subtle text-primary
                                            @elseif($topic->category === 'leadership') bg-warning-subtle text-warning
                                            @elseif($topic->category === 'compliance') bg-danger-subtle text-danger
                                            @elseif($topic->category === 'soft_skills') bg-success-subtle text-success
                                            @else bg-secondary-subtle text-secondary
                                            @endif
                                            ">
                                            {{ ucwords(str_replace('_', ' ', $topic->category)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($topic->rank_level === 'all')
                                            <span class="badge bg-info-subtle text-info">All Employees</span>
                                        @elseif($topic->rank_level === 'higher')
                                            <span class="badge bg-purple-subtle text-purple">Higher Rank</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Normal Rank</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-journal-text me-1"></i>{{ $topic->trainings_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('training-topics.toggle-active', $topic) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm border-0 p-0">
                                                @if($topic->is_active)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-check-circle me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="bi bi-x-circle me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('training-topics.show', $topic) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('training-topics.edit', $topic) }}" class="btn btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($topic->trainings_count === 0)
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $topic->id }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $topic->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the topic <strong>{{ $topic->title }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('training-topics.destroy', $topic) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top">
                    {{ $topics->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bookmarks" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="mt-3">No training topics yet</p>
                    <a href="{{ route('training-topics.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add First Topic
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
