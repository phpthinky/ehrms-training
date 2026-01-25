@extends('layouts.app')

@section('title', 'Add Training Topic - EHRMS')
@section('page-title', 'Add Training Topic')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training-topics.index') }}">Training Topics</a></li>
            <li class="breadcrumb-item active">Add New Topic</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-bookmark-plus text-primary me-2"></i>New Training Topic
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('training-topics.store') }}" method="POST">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Topic Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Leadership and Management, Microsoft Excel" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter a clear, descriptive name for this training topic</small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="3" placeholder="Brief description of what this topic covers">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>
                                    <i class="bi bi-cpu"></i> Technical
                                </option>
                                <option value="soft_skills" {{ old('category') === 'soft_skills' ? 'selected' : '' }}>
                                    Soft Skills
                                </option>
                                <option value="compliance" {{ old('category') === 'compliance' ? 'selected' : '' }}>
                                    Compliance
                                </option>
                                <option value="leadership" {{ old('category') === 'leadership' ? 'selected' : '' }}>
                                    Leadership
                                </option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <strong>Categories:</strong><br>
                                • <strong>Technical</strong> - Computer skills, software, systems<br>
                                • <strong>Soft Skills</strong> - Communication, teamwork, problem-solving<br>
                                • <strong>Compliance</strong> - Legal requirements, policies, procedures<br>
                                • <strong>Leadership</strong> - Management, strategy, decision-making<br>
                                • <strong>Other</strong> - Topics that don't fit above categories
                            </small>
                        </div>

                        <!-- Rank Level -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Rank Level Eligibility <span class="text-danger">*</span></label>
                            <select name="rank_level" class="form-select @error('rank_level') is-invalid @enderror" required>
                                <option value="">Select Rank Level</option>
                                <option value="all" {{ old('rank_level') === 'all' ? 'selected' : '' }}>
                                    All Employees
                                </option>
                                <option value="higher" {{ old('rank_level') === 'higher' ? 'selected' : '' }}>
                                    Higher Rank Only
                                </option>
                                <option value="normal" {{ old('rank_level') === 'normal' ? 'selected' : '' }}>
                                    Normal Rank Only
                                </option>
                            </select>
                            @error('rank_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Who is eligible to attend trainings under this topic?</small>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Active Topic
                                </label>
                            </div>
                            <small class="text-muted">Active topics can be selected when creating trainings and surveys</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Topic
                            </button>
                            <a href="{{ route('training-topics.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>About Training Topics</h6>
                    <p class="small mb-3">Training topics are used to categorize and organize trainings across your organization.</p>

                    <h6 class="mb-2 small fw-semibold">How They're Used:</h6>
                    <ul class="small">
                        <li class="mb-2">When <strong>scheduling trainings</strong>, HR can assign a topic</li>
                        <li class="mb-2">Employees can <strong>filter trainings</strong> by topic</li>
                        <li class="mb-2">Used in <strong>training surveys</strong> to gather employee interests</li>
                        <li class="mb-2">Generate <strong>reports</strong> grouped by topic</li>
                    </ul>

                    <h6 class="mb-2 small fw-semibold mt-4">Best Practices:</h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Use clear, concise names</li>
                        <li class="mb-2">Group similar subjects together</li>
                        <li class="mb-2">Set appropriate rank levels</li>
                        <li class="mb-2">Deactivate instead of deleting topics with existing trainings</li>
                    </ul>
                </div>
            </div>

            <!-- Examples -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>Examples</h6>
                    <div class="small">
                        <strong>Technical Topics:</strong>
                        <ul class="mb-3">
                            <li>Microsoft Office Suite</li>
                            <li>Data Analytics</li>
                            <li>Records Management</li>
                        </ul>

                        <strong>Leadership Topics:</strong>
                        <ul class="mb-3">
                            <li>Strategic Planning</li>
                            <li>Performance Management</li>
                            <li>Change Management</li>
                        </ul>

                        <strong>Compliance Topics:</strong>
                        <ul class="mb-0">
                            <li>Data Privacy Act</li>
                            <li>Anti-Corruption</li>
                            <li>Health and Safety</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
