@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h3 class="mt-4">No Active Survey</h3>
            <p class="text-muted">There is no active training needs assessment survey at this time.</p>
            <p class="text-muted">Please check back later or contact HR for more information.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                <i class="bi bi-house-door me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
