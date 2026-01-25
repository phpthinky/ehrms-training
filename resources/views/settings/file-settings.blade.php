@extends('layouts.app')

@section('title', '201 Files Settings - EHRMS')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">201 Files Settings</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-gear text-primary me-2"></i>201 Files Settings
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Configure employee 201 files upload permissions</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('settings.file-settings.update') }}" method="POST">
                        @csrf

                        @foreach($settings as $setting)
                            @if($setting->key === 'allow_employee_file_upload')
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">
                                                <i class="bi bi-cloud-upload me-2 text-primary"></i>
                                                {{ $setting->label }}
                                            </h6>
                                            <p class="text-muted small mb-3">
                                                {{ $setting->description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-check form-switch" style="font-size: 1.2rem;">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               id="allow_employee_file_upload"
                                               name="allow_employee_file_upload"
                                               value="1"
                                               {{ $setting->value == '1' ? 'checked' : '' }}
                                               onchange="this.form.querySelector('input[name=allow_employee_file_upload]').value = this.checked ? 1 : 0;">
                                        <label class="form-check-label" for="allow_employee_file_upload">
                                            <span class="fw-semibold" id="status-label">
                                                {{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </label>
                                        <!-- Hidden input to ensure value is always sent -->
                                        <input type="hidden" name="allow_employee_file_upload" value="{{ $setting->value }}">
                                    </div>

                                    <div class="mt-3 p-3 rounded {{ $setting->value == '1' ? 'bg-success-subtle' : 'bg-warning-subtle' }}">
                                        @if($setting->value == '1')
                                            <div class="d-flex">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <div>
                                                    <strong class="text-success">Currently Enabled</strong>
                                                    <p class="mb-0 small text-muted mt-1">
                                                        Employees can upload documents to their own 201 files.
                                                        HR can still upload on their behalf.
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex">
                                                <i class="bi bi-shield-lock text-warning me-2"></i>
                                                <div>
                                                    <strong class="text-warning">Currently Disabled</strong>
                                                    <p class="mb-0 small text-muted mt-1">
                                                        Only HR Admin and Admin Assistant can upload documents.
                                                        Employees can only view and download their files.
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Information Box -->
                                <div class="alert alert-info border-0">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>Important Information
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li><strong>Security:</strong> When disabled, HR maintains full control over document verification.</li>
                                        <li><strong>Compliance:</strong> Enabling employee uploads may affect document authentication process.</li>
                                        <li><strong>Recommendation:</strong> Keep disabled for official government documents (PDS, TOR, NBI, etc.)</li>
                                        <li><strong>Use Case:</strong> Enable if you want employees to upload training certificates or personal documents.</li>
                                    </ul>
                                </div>
                            @endif
                        @endforeach

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
                        <i class="bi bi-lightbulb text-warning me-2"></i>Settings Guide
                    </h6>

                    <div class="mb-3">
                        <strong class="d-block mb-2">When to Enable Employee Uploads:</strong>
                        <ul class="small text-muted mb-0">
                            <li>Trust employees with their documents</li>
                            <li>Want to speed up onboarding process</li>
                            <li>Collect non-critical documents</li>
                            <li>Reduce HR workload</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <strong class="d-block mb-2">When to Keep Disabled:</strong>
                        <ul class="small text-muted mb-0">
                            <li>Need document verification by HR</li>
                            <li>Government compliance requirements</li>
                            <li>Security and authenticity concerns</li>
                            <li>Official CSC 201 file requirements</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning alert-sm mb-0">
                        <small>
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Note:</strong> You can change this setting at any time.
                            Existing files are not affected.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update status label when toggle changes
    document.getElementById('allow_employee_file_upload').addEventListener('change', function() {
        const label = document.getElementById('status-label');
        const hiddenInput = document.querySelector('input[type=hidden][name=allow_employee_file_upload]');

        if (this.checked) {
            label.textContent = 'Enabled';
            hiddenInput.value = '1';
        } else {
            label.textContent = 'Disabled';
            hiddenInput.value = '0';
        }
    });
</script>
@endsection
