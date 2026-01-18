@extends('layouts.app')

@section('title', 'Employee Training Report - EHRMS')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Employee Training History</h5>
            <form action="{{ route('reports.export.employee') }}" method="GET">
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="bi bi-download"></i> Export CSV
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Total</th>
                            <th>Attended</th>
                            <th>Certificates</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeeData as $data)
                            <tr>
                                <td>{{ $data['employee']->first_name }} {{ $data['employee']->last_name }}</td>
                                <td>{{ $data['employee']->department->name ?? 'N/A' }}</td>
                                <td>{{ $data['total_trainings'] }}</td>
                                <td><span class="badge bg-success">{{ $data['attended'] }}</span></td>
                                <td>{{ $data['certificates'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
