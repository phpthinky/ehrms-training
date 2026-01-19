@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="h3">Create Survey Template</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('survey-templates.index') }}">Survey Templates</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('survey-templates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror"
                                   id="year" name="year" value="{{ old('year', $suggestedYear) }}"
                                   min="2020" max="2100" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">One survey template per year</small>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}"
                                   placeholder="e.g., Training Needs Assessment 2026" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description of this survey's purpose">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                       name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Set as Active Survey
                                </label>
                            </div>
                            <small class="text-muted">Only one survey can be active at a time. This will deactivate other surveys.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Template
                            </button>
                            <a href="{{ route('survey-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightbulb me-2"></i>Next Steps</h5>
                    <ol class="mb-0">
                        <li class="mb-2">Create the template</li>
                        <li class="mb-2">Add questions from the question bank</li>
                        <li class="mb-2">Arrange questions in desired order</li>
                        <li class="mb-2">Mark required questions</li>
                        <li>Activate to make available to employees</li>
                    </ol>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="bi bi-info-circle me-2"></i>Tips</h6>
                    <ul class="small mb-0">
                        <li>Use a clear, descriptive title</li>
                        <li>Include the year for easy identification</li>
                        <li>Don't activate until survey is complete</li>
                        <li>You can duplicate last year's survey</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
