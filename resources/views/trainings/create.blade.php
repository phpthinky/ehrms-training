@extends('layouts.app')

@section('title', 'Schedule Training - EHRMS')
@section('page-title', 'Schedule New Training')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Trainings</a></li>
            <li class="breadcrumb-item active">Schedule Training</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-journal-plus text-primary me-2"></i>Training Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('trainings.store') }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Basic Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Training Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Leadership and Management Training" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Brief description of the training">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Training Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Internal (LGU Hosted)</option>
                                    <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>External (Request for Attendance)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Training Topic</label>
                                <select name="training_topic_id" class="form-select @error('training_topic_id') is-invalid @enderror">
                                    <option value="">Select Topic (Optional)</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}" {{ old('training_topic_id') == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_topic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rank Level Eligibility <span class="text-danger">*</span></label>
                                <select name="rank_level" class="form-select @error('rank_level') is-invalid @enderror" required>
                                    <option value="">Select Rank Level</option>
                                    <option value="all" {{ old('rank_level') == 'all' ? 'selected' : '' }}>All Employees</option>
                                    <option value="higher" {{ old('rank_level') == 'higher' ? 'selected' : '' }}>Higher Rank Only</option>
                                    <option value="normal" {{ old('rank_level') == 'normal' ? 'selected' : '' }}>Normal Rank Only</option>
                                </select>
                                @error('rank_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Schedule & Venue -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Schedule & Venue</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave blank for single-day training</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Time</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Time</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Venue</label>
                                <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue') }}" placeholder="e.g., LGU Conference Hall">
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Facilitator</label>
                                <input type="text" name="facilitator" class="form-control @error('facilitator') is-invalid @enderror" value="{{ old('facilitator') }}" placeholder="Facilitator name">
                                @error('facilitator')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Any additional information or requirements">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('trainings.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Schedule Training
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Training Types
                    </h6>
                    
                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Internal Training:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Hosted by LGU Sablayan</li>
                            <li>Conducted in-house</li>
                            <li>Can mark attendance</li>
                            <li>Status: Scheduled → Ongoing → Completed</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>External Training:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Hosted by external organization</li>
                            <li>Requires approval request</li>
                            <li>Employee attends externally</li>
                            <li>Certificate uploaded after completion</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <small class="d-block mb-2"><strong>Rank Level:</strong></small>
                        <ul class="small text-muted ps-3 mb-0">
                            <li><strong>All:</strong> All employees eligible</li>
                            <li><strong>Higher:</strong> Managers, supervisors only</li>
                            <li><strong>Normal:</strong> Regular employees only</li>
                        </ul>
                    </div>

                    <div class="alert alert-info alert-sm mb-0">
                        <small><i class="bi bi-lightbulb me-2"></i><strong>Tip:</strong> You can manage attendees after creating the training.</small>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-graph-up text-primary me-2"></i>Training Statistics
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">This Year</span>
                        <strong>{{ \App\Models\Training::whereYear('start_date', date('Y'))->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Ongoing</span>
                        <strong>{{ \App\Models\Training::where('status', 'ongoing')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Completed</span>
                        <strong>{{ \App\Models\Training::where('status', 'completed')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
