@extends('layouts.app')

@section('title', 'Messages - EHRMS')
@section('page-title', 'Internal Messages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Message List -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1" style="font-family: 'Outfit', sans-serif; font-weight: 600;">Messages</h4>
                    <p class="text-muted mb-0">Internal communication system</p>
                </div>
                <a href="{{ route('messages.create') }}" class="btn btn-primary">
                    <i class="bi bi-envelope-plus me-2"></i>Compose
                </a>
            </div>

            <!-- Message Filter Tabs -->
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-filter="all" onclick="filterMessages('all')">
                        All Messages
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-filter="unread" onclick="filterMessages('unread')">
                        Unread
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-filter="sent" onclick="filterMessages('sent')">
                        Sent
                    </button>
                </li>
            </ul>

            <!-- Messages Card -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($messages as $message)
                            <a href="{{ route('messages.show', $message) }}" class="list-group-item list-group-item-action {{ !$message->is_read && $message->receiver_id == auth()->id() ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-start">
                                    <!-- Avatar -->
                                    <div class="avatar me-3" style="width: 45px; height: 45px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                                        @php
                                            $otherUser = $message->sender_id == auth()->id() ? $message->receiver : $message->sender;
                                            $initials = strtoupper(substr($otherUser->name, 0, 1) . (strpos($otherUser->name, ' ') ? substr($otherUser->name, strpos($otherUser->name, ' ') + 1, 1) : ''));
                                        @endphp
                                        {{ $initials }}
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <strong class="d-block">
                                                    @if($message->sender_id == auth()->id())
                                                        To: {{ $message->receiver->name }}
                                                    @else
                                                        {{ $message->sender->name }}
                                                    @endif
                                                </strong>
                                                @if($message->sender_id == auth()->id())
                                                    <small class="text-muted">
                                                        <i class="bi bi-arrow-right me-1"></i>Sent
                                                    </small>
                                                @endif
                                            </div>
                                            <small class="text-muted text-nowrap ms-2">
                                                {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                        
                                        <h6 class="mb-1 {{ !$message->is_read && $message->receiver_id == auth()->id() ? 'fw-bold' : '' }}">
                                            {{ $message->subject }}
                                        </h6>
                                        
                                        <p class="text-muted mb-0 small text-truncate">
                                            {{ Str::limit(strip_tags($message->body), 100) }}
                                        </p>
                                        
                                        @if(!$message->is_read && $message->receiver_id == auth()->id())
                                            <span class="badge bg-primary mt-2">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.2;"></i>
                                <p class="mt-3 text-muted">No messages yet</p>
                                <a href="{{ route('messages.create') }}" class="btn btn-primary btn-sm mt-2">
                                    Send your first message
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($messages->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-bar-chart text-primary me-2"></i>Message Stats
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Messages</span>
                        <strong>{{ $messages->total() ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Unread</span>
                        <strong>{{ \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Sent Today</span>
                        <strong>{{ \App\Models\Message::where('sender_id', auth()->id())->whereDate('created_at', today())->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('messages.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope-plus me-2"></i>New Message
                        </a>
                        @if(auth()->user()->isStaff())
                            <button class="btn btn-outline-secondary btn-sm" onclick="alert('Feature coming soon')">
                                <i class="bi bi-people me-2"></i>Message All Employees
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card border-0 bg-light mt-3">
                <div class="card-body">
                    <h6 class="mb-2" style="font-weight: 600;">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips
                    </h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Use clear subject lines</li>
                        <li>Keep messages concise</li>
                        <li>Check messages regularly</li>
                        <li>Reply promptly to urgent matters</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterMessages(type) {
    // Update active tab
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-filter="${type}"]`).classList.add('active');
    
    // Filter logic would go here
    // For now, just reload with filter parameter
    if (type !== 'all') {
        window.location.href = "{{ route('messages.index') }}?filter=" + type;
    } else {
        window.location.href = "{{ route('messages.index') }}";
    }
}
</script>
@endpush
@endsection
