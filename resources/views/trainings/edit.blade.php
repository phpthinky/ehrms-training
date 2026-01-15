@extends('layouts.app')

@section('title', 'Edit Training - EHRMS')
@section('page-title', 'Edit Training')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Trainings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainings.show', $training) }}">{{ Str::limit($training->title, 30) }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-pencil text-primary me-2"></i>Edit Training Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('trainings.update', $training) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Basic Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Training Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $training->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $training->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="scheduled" {{ old('status', $training->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="ongoing" {{ old('status', $training->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $training->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $training->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Schedule & Venue -->
                        <h6 class="mb-3 text-primary" style="font-weight: 600;">Schedule & Venue</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $training->start_date) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $training->end_date) }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Time</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $training->start_time) }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Time</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $training->end_time) }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Venue</label>
                                <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue', $training->venue) }}">
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Facilitator</label>
                                <input type="text" name="facilitator" class="form-control @error('facilitator') is-invalid @enderror" value="{{ old('facilitator', $training->facilitator) }}">
                                @error('facilitator')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $training->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('trainings.show', $training) }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Update Training
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Training Details
                    </h6>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Training Type</small>
                        @if($training->type === 'internal')
                            <span class="badge bg-primary">Internal</span>
                        @else
                            <span class="badge bg-info">External</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Rank Level</small>
                        <strong>{{ ucfirst($training->rank_level) }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Topic</small>
                        <strong>{{ $training->topic->title ?? 'Not specified' }}</strong>
                    </div>

                    <div class="alert alert-info alert-sm mb-0">
                        <small><i class="bi bi-lightbulb me-2"></i><strong>Note:</strong> Type, rank level, and topic cannot be changed after creation.</small>
                    </div>
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-people text-primary me-2"></i>Attendees
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total</span>
                        <strong>{{ $training->attendances->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Attended</span>
                        <strong class="text-success">{{ $training->attendances->where('attendance_status', 'attended')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Pending</span>
                        <strong class="text-warning">{{ $training->attendances->where('attendance_status', 'pending')->count() }}</strong>
                    </div>
                </div>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-clock-history text-primary me-2"></i>Activity
                    </h6>
                    <small class="text-muted d-block mb-2">
                        <strong>Created by:</strong> {{ $training->creator->name }}
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
@endsection
