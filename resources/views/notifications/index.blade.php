@extends('layouts.app')

@section('title', 'Notifications - EHRMS')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">Notifications</h4>
                    <p class="text-muted mb-0">System alerts and updates</p>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-check-all me-2"></i>Mark All as Read
                        </button>
                    </form>
                @endif
            </div>

            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            <div class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-start">
                                    <!-- Icon based on type -->
                                    <div class="me-3 flex-shrink-0">
                                        @if(str_contains($notification->type, 'training'))
                                            <div class="icon-box" style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-journal-bookmark text-primary"></i>
                                            </div>
                                        @elseif(str_contains($notification->type, 'document'))
                                            <div class="icon-box" style="width: 40px; height: 40px; background: rgba(5, 150, 105, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-file-earmark text-success"></i>
                                            </div>
                                        @elseif(str_contains($notification->type, 'message'))
                                            <div class="icon-box" style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-envelope text-warning"></i>
                                            </div>
                                        @else
                                            <div class="icon-box" style="width: 40px; height: 40px; background: rgba(100, 116, 139, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-bell text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Notification Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <strong>{{ $notification->title }}</strong>
                                            <small class="text-muted text-nowrap ms-2">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                        <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                        
                                        <div class="d-flex gap-2">
                                            @if(!$notification->is_read)
                                                <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-check2 me-1"></i>Mark as Read
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Read</span>
                                            @endif
                                            
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash" style="font-size: 4rem; opacity: 0.2;"></i>
                                <p class="mt-3 text-muted">No notifications yet</p>
                                <small class="text-muted">You'll receive notifications for important updates</small>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($notifications->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>Notification Stats
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total</span>
                        <strong>{{ $notifications->total() ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Unread</span>
                        <strong class="text-primary">{{ $notifications->where('is_read', false)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Today</span>
                        <strong>{{ $notifications->where('created_at', '>=', today())->count() }}</strong>
                    </div>
                </div>
            </div>

            <div class="card border-0 bg-light mt-3">
                <div class="card-body">
                    <h6 class="mb-2" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>About Notifications
                    </h6>
                    <p class="small text-muted mb-2">You'll receive notifications for:</p>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Training schedules and updates</li>
                        <li>Document submissions</li>
                        <li>Important announcements</li>
                        <li>System updates</li>
                        <li>Action required items</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
