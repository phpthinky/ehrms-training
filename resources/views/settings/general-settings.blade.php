@extends('layouts.app')

@section('title', 'General Settings - EHRMS')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">General Settings</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-gear text-primary me-2"></i>General Settings
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Customize your application name, logo, and branding</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('settings.general-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @php
                            $settingsData = [];
                            foreach($settings as $setting) {
                                $settingsData[$setting->key] = $setting;
                            }
                        @endphp

                        <!-- Application Name -->
                        <div class="mb-4">
                            <label for="app_name" class="form-label fw-bold">
                                <i class="bi bi-app-indicator text-primary me-2"></i>
                                {{ $settingsData['app_name']->label ?? 'Application Name' }}
                            </label>
                            <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                   id="app_name" name="app_name"
                                   value="{{ old('app_name', $settingsData['app_name']->value ?? 'EHRMS') }}"
                                   placeholder="e.g., EHRMS" required>
                            <small class="text-muted">{{ $settingsData['app_name']->description ?? '' }}</small>
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Application Short Name -->
                        <div class="mb-4">
                            <label for="app_short_name" class="form-label fw-bold">
                                <i class="bi bi-type text-primary me-2"></i>
                                {{ $settingsData['app_short_name']->label ?? 'Application Short Name' }}
                            </label>
                            <input type="text" class="form-control @error('app_short_name') is-invalid @enderror"
                                   id="app_short_name" name="app_short_name"
                                   value="{{ old('app_short_name', $settingsData['app_short_name']->value ?? 'EHRMS') }}"
                                   placeholder="e.g., EHRMS" maxlength="50" required>
                            <small class="text-muted">{{ $settingsData['app_short_name']->description ?? '' }}</small>
                            @error('app_short_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Application Tagline -->
                        <div class="mb-4">
                            <label for="app_tagline" class="form-label fw-bold">
                                <i class="bi bi-chat-quote text-primary me-2"></i>
                                {{ $settingsData['app_tagline']->label ?? 'Application Tagline' }}
                            </label>
                            <input type="text" class="form-control @error('app_tagline') is-invalid @enderror"
                                   id="app_tagline" name="app_tagline"
                                   value="{{ old('app_tagline', $settingsData['app_tagline']->value ?? '') }}"
                                   placeholder="e.g., Employee Human Resource Management System">
                            <small class="text-muted">{{ $settingsData['app_tagline']->description ?? '' }}</small>
                            @error('app_tagline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Organization Name -->
                        <div class="mb-4">
                            <label for="organization_name" class="form-label fw-bold">
                                <i class="bi bi-building text-primary me-2"></i>
                                {{ $settingsData['organization_name']->label ?? 'Organization Name' }}
                            </label>
                            <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                                   id="organization_name" name="organization_name"
                                   value="{{ old('organization_name', $settingsData['organization_name']->value ?? 'LGU Sablayan') }}"
                                   placeholder="e.g., LGU Sablayan" required>
                            <small class="text-muted">{{ $settingsData['organization_name']->description ?? '' }}</small>
                            @error('organization_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Application Logo -->
                        <div class="mb-4">
                            <label for="app_logo" class="form-label fw-bold">
                                <i class="bi bi-image text-primary me-2"></i>
                                {{ $settingsData['app_logo']->label ?? 'Application Logo' }}
                            </label>

                            @if(!empty($settingsData['app_logo']->value))
                                <div class="mb-3 p-3 bg-light rounded">
                                    <p class="small mb-2"><strong>Current Logo:</strong></p>
                                    <img src="{{ asset($settingsData['app_logo']->value) }}" alt="Current Logo"
                                         style="max-height: 80px; max-width: 200px;" class="border rounded p-2 bg-white">
                                </div>
                            @endif

                            <input type="file" class="form-control @error('app_logo') is-invalid @enderror"
                                   id="app_logo" name="app_logo" accept="image/*">
                            <small class="text-muted">
                                {{ $settingsData['app_logo']->description ?? '' }}
                                <br>Supported formats: JPEG, PNG, JPG, SVG. Max size: 2MB.
                            </small>
                            @error('app_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Information Box -->
                        <div class="alert alert-info border-0 mt-4">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i>Branding Guidelines
                            </h6>
                            <ul class="mb-0 small">
                                <li><strong>Logo:</strong> Use transparent background PNG for best results</li>
                                <li><strong>Size:</strong> Recommended logo dimensions: 200x60 pixels</li>
                                <li><strong>Short Name:</strong> Displayed in sidebar when collapsed</li>
                                <li><strong>Changes:</strong> All changes take effect immediately across the application</li>
                            </ul>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('dashboard') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Customization Tips
                    </h6>

                    <div class="mb-3">
                        <strong class="d-block mb-2">Application Name:</strong>
                        <ul class="small text-muted mb-0">
                            <li>Appears in browser tab title</li>
                            <li>Displayed in header navigation</li>
                            <li>Used in email notifications</li>
                            <li>Shows in login page</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <strong class="d-block mb-2">Logo Best Practices:</strong>
                        <ul class="small text-muted mb-0">
                            <li>Use PNG with transparent background</li>
                            <li>Horizontal orientation works best</li>
                            <li>Keep file size under 500KB</li>
                            <li>Test on both light and dark backgrounds</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning alert-sm mb-0">
                        <small>
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Note:</strong> Changes are visible to all users immediately after saving.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
