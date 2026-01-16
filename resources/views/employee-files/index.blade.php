@extends('layouts.app')

@section('title', '201 Files - EHRMS')
@section('page-title', 'Employee 201 Files')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->getFullNameAttribute() }}</a></li>
            <li class="breadcrumb-item active">201 Files</li>
        </ol>
    </nav>

    <!-- Employee Header -->
    <div class="card border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-center">
                    <!-- Avatar -->
                    <div class="avatar me-3" style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.2rem;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-weight: 600;">{{ $employee->getFullNameAttribute() }}</h5>
                        <div class="text-muted small">
                            <i class="bi bi-person-badge me-1"></i>{{ $employee->employee_number }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-briefcase me-1"></i>{{ $employee->position }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-building me-1"></i>{{ $employee->department->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                @if(auth()->user()->isStaff())
                    <a href="{{ route('employee-files.create', $employee) }}" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-2"></i>Upload File
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Files by Category -->
        <div class="col-lg-9">
            @php
                $categories = [
                    'pds' => ['name' => 'Personal Data Sheet (PDS)', 'icon' => 'bi-file-person', 'color' => 'primary'],
                    'tor' => ['name' => 'Transcript of Records (TOR)', 'icon' => 'bi-file-earmark-text', 'color' => 'info'],
                    'diploma' => ['name' => 'Diploma', 'icon' => 'bi-award', 'color' => 'success'],
                    'certificate' => ['name' => 'Certificates', 'icon' => 'bi-patch-check', 'color' => 'warning'],
                    'nbi_clearance' => ['name' => 'NBI Clearance', 'icon' => 'bi-shield-check', 'color' => 'danger'],
                    'medical_certificate' => ['name' => 'Medical Certificate', 'icon' => 'bi-heart-pulse', 'color' => 'success'],
                    'tax_identification' => ['name' => 'Tax Identification (TIN)', 'icon' => 'bi-receipt', 'color' => 'secondary'],
                    'birth_certificate' => ['name' => 'Birth Certificate', 'icon' => 'bi-file-earmark', 'color' => 'primary'],
                    'marriage_certificate' => ['name' => 'Marriage Certificate', 'icon' => 'bi-file-earmark-heart', 'color' => 'danger'],
                    'service_record' => ['name' => 'Service Record', 'icon' => 'bi-journal-text', 'color' => 'info'],
                    'other' => ['name' => 'Other Documents', 'icon' => 'bi-files', 'color' => 'secondary'],
                ];
            @endphp

            @foreach($categories as $type => $category)
                @php
                    $files = $filesByType[$type] ?? collect();
                @endphp
                
                <div class="card border-0 mb-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi {{ $category['icon'] }} text-{{ $category['color'] }} me-2"></i>
                                {{ $category['name'] }}
                                <span class="badge bg-{{ $category['color'] }}-subtle text-{{ $category['color'] }} ms-2">
                                    {{ $files->count() }}
                                </span>
                            </h6>
                        </div>
                    </div>
                    @if($files->count() > 0)
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($files as $file)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <!-- File Icon -->
                                                <div class="me-3">
                                                    @php
                                                        $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                                    @endphp
                                                    @if($ext === 'pdf')
                                                        <i class="bi bi-file-pdf text-danger" style="font-size: 2rem;"></i>
                                                    @elseif(in_array($ext, ['jpg', 'jpeg', 'png']))
                                                        <i class="bi bi-file-image text-primary" style="font-size: 2rem;"></i>
                                                    @elseif(in_array($ext, ['doc', 'docx']))
                                                        <i class="bi bi-file-word text-info" style="font-size: 2rem;"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark text-secondary" style="font-size: 2rem;"></i>
                                                    @endif
                                                </div>
                                                
                                                <!-- File Info -->
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">{{ $file->file_name }}</div>
                                                    <div class="small text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>{{ $file->created_at->format('M d, Y') }}
                                                        <span class="mx-2">•</span>
                                                        <i class="bi bi-hdd me-1"></i>{{ number_format($file->file_size / 1024, 2) }} KB
                                                        @if($file->remarks)
                                                            <span class="mx-2">•</span>
                                                            {{ Str::limit($file->remarks, 50) }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('employee-files.download', $file) }}" class="btn btn-outline-primary" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                @if(auth()->user()->isStaff())
                                                    <form action="{{ route('employee-files.destroy', $file) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this file?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="card-body text-center py-4 text-muted">
                            <i class="bi {{ $category['icon'] }}" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2 small">No {{ strtolower($category['name']) }} uploaded yet</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Statistics -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>File Statistics
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Files</span>
                        <strong>{{ $employee->files->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Size</span>
                        <strong>{{ number_format($employee->files->sum('file_size') / 1024 / 1024, 2) }} MB</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Last Upload</span>
                        <strong>{{ $employee->files->max('created_at') ? $employee->files->max('created_at')->diffForHumans() : 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(auth()->user()->isStaff())
                <div class="card border-0 mb-3">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600;">
                            <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('employee-files.create', $employee) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cloud-upload me-2"></i>Upload File
                            </a>
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-person me-2"></i>View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 201 File Checklist -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-check2-square text-primary me-2"></i>201 File Checklist
                    </h6>
                    <div class="small">
                        @foreach($categories as $type => $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ $category['name'] }}</span>
                                @if(isset($filesByType[$type]) && $filesByType[$type]->count() > 0)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-circle text-muted"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        @php
                            $completionRate = ($filesByType->keys()->count() / count($categories)) * 100;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $completionRate }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2 text-center">
                        {{ round($completionRate) }}% Complete
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
