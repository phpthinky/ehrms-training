@extends('layouts.app')

@section('title', 'View Message - EHRMS')
@section('page-title', 'Message')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('messages.index') }}">Messages</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($message->subject, 30) }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                            {{ $message->subject }}
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if($message->receiver_id === auth()->id())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('messages.create') }}?reply_to={{ $message->id }}">
                                            <i class="bi bi-reply me-2"></i>Reply
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('messages.destroy', $message) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Message Header -->
                    <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                        <!-- Sender Avatar -->
                        <div class="avatar me-3" style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                            @php
                                $name = $message->sender->name;
                                $initials = strtoupper(substr($name, 0, 1) . (strpos($name, ' ') ? substr($name, strpos($name, ' ') + 1, 1) : ''));
                            @endphp
                            {{ $initials }}
                        </div>

                        <!-- Sender Info -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="d-block">{{ $message->sender->name }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i>{{ $message->sender->email }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y') }}
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('g:i A') }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <small class="text-muted">
                                    <strong>To:</strong> {{ $message->receiver->name }}
                                </small>
                            </div>

                            @if(!$message->is_read && $message->receiver_id === auth()->id())
                                <span class="badge bg-primary mt-2">New</span>
                            @endif
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="message-body" style="line-height: 1.8;">
                        {!! nl2br(e($message->body)) !!}
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
                        <a href="{{ route('messages.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-2"></i>Back to Inbox
                        </a>
                        @if($message->receiver_id === auth()->id())
                            <a href="{{ route('messages.create') }}?reply_to={{ $message->id }}" class="btn btn-primary">
                                <i class="bi bi-reply me-2"></i>Reply
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Message Info -->
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Message Info
                    </h6>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        @if($message->is_read)
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="bi bi-check-all me-1"></i>Read
                            </span>
                            @if($message->read_at)
                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($message->read_at)->format('M d, Y g:i A') }}</small>
                            @endif
                        @else
                            <span class="badge bg-primary-subtle text-primary">
                                <i class="bi bi-envelope me-1"></i>Unread
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Message ID</small>
                        <code>#{{ $message->id }}</code>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Sent</small>
                        <strong>{{ $message->created_at->diffForHumans() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Conversation Thread -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-chat-text text-primary me-2"></i>Conversation
                    </h6>
                    
                    @php
                        $otherUserId = $message->sender_id === auth()->id() ? $message->receiver_id : $message->sender_id;
                        $relatedMessages = \App\Models\Message::where(function($q) use ($otherUserId) {
                            $q->where(function($q2) use ($otherUserId) {
                                $q2->where('sender_id', auth()->id())
                                   ->where('receiver_id', $otherUserId);
                            })->orWhere(function($q2) use ($otherUserId) {
                                $q2->where('sender_id', $otherUserId)
                                   ->where('receiver_id', auth()->id());
                            });
                        })
                        ->where('id', '!=', $message->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                    @endphp

                    @if($relatedMessages->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedMessages as $related)
                                <a href="{{ route('messages.show', $related) }}" class="list-group-item list-group-item-action px-0 py-2 border-0">
                                    <small class="d-block fw-semibold">{{ Str::limit($related->subject, 40) }}</small>
                                    <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="small text-muted mb-0">No other messages in this conversation</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 bg-light mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        @if($message->receiver_id === auth()->id())
                            <a href="{{ route('messages.create') }}?reply_to={{ $message->id }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-reply me-2"></i>Reply to Message
                            </a>
                            @if(!$message->is_read)
                                <form action="{{ route('messages.mark-read', $message) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                                        <i class="bi bi-check2 me-2"></i>Mark as Read
                                    </button>
                                </form>
                            @endif
                        @endif
                        <a href="{{ route('messages.create') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-envelope-plus me-2"></i>New Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
