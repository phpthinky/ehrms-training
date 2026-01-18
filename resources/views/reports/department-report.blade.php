@extends('layouts.app')

@section('title', 'Department Report - EHRMS')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Department Statistics</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Department</th>
                            <th>Employees</th>
                            <th>Total Trainings</th>
                            <th>Avg per Employee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departmentData as $data)
                            <tr>
                                <td>{{ $data['department']->name }}</td>
                                <td>{{ $data['employee_count'] }}</td>
                                <td>{{ $data['total_trainings'] }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $data['average_per_employee'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
