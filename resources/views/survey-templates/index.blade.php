@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Survey Templates</h1>
            <p class="text-muted">Manage annual training needs assessment surveys</p>
        </div>
        <a href="{{ route('survey-templates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create New Template
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Responses</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td><strong>{{ $template->year }}</strong></td>
                                <td>
                                    <a href="{{ route('survey-templates.show', $template) }}" class="text-decoration-none">
                                        {{ $template->title }}
                                    </a>
                                    @if($template->description)
                                        <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $template->questions_count }} Questions</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $template->responses_count }} Responses</span>
                                </td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $template->creator->name }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('survey-templates.show', $template) }}"
                                           class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('survey-builder.index', $template) }}"
                                           class="btn btn-outline-info" title="Build Survey">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('survey-templates.edit', $template) }}"
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-gear"></i>
                                        </a>
                                        <form action="{{ route('survey-templates.toggle-active', $template) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-{{ $template->is_active ? 'secondary' : 'success' }}"
                                                    title="{{ $template->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $template->is_active ? 'pause' : 'play' }}-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted">No survey templates yet. Create your first template!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $templates->links() }}
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Guide</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6><i class="bi bi-1-circle text-primary"></i> Create Template</h6>
                    <p class="small text-muted">Set up a new annual survey</p>
                </div>
                <div class="col-md-3">
                    <h6><i class="bi bi-2-circle text-primary"></i> Build Survey</h6>
                    <p class="small text-muted">Add questions from question bank</p>
                </div>
                <div class="col-md-3">
                    <h6><i class="bi bi-3-circle text-primary"></i> Activate</h6>
                    <p class="small text-muted">Make it available to employees</p>
                </div>
                <div class="col-md-3">
                    <h6><i class="bi bi-4-circle text-primary"></i> View Analytics</h6>
                    <p class="small text-muted">Track responses and insights</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
