@extends('layouts.app')

@section('title', 'Account Settings - EHRMS')
@section('page-title', 'Account Settings')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back to Profile -->
            <div class="mb-3">
                <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to Profile
                </a>
            </div>

            <!-- Update Name -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-person text-primary me-2"></i>Update Name
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update-name') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Display Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This is how your name will appear in the system.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Name
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Email -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-envelope text-primary me-2"></i>Update Email
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update-email') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Enter new email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Enter your current password to confirm" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">For security, please enter your current password.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Email
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-shield-lock text-primary me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Enter your current password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter new password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Confirm new password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-info-circle text-muted me-2"></i>Account Information
                    </h6>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <span class="text-muted">Account Created:</span>
                            <span class="fw-semibold ms-2">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted">Last Updated:</span>
                            <span class="fw-semibold ms-2">{{ $user->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted">Role:</span>
                            <span class="badge bg-primary ms-2">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                        </div>
                        @if($employee)
                            <div class="col-md-6">
                                <span class="text-muted">Employee #:</span>
                                <span class="fw-semibold ms-2">{{ $employee->employee_number }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
