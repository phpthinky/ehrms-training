@extends('layouts.app')

@section('title', 'Compose Message - EHRMS')
@section('page-title', 'Compose Message')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('messages.index') }}">Messages</a></li>
            <li class="breadcrumb-item active">Compose</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600;">
                        <i class="bi bi-envelope-plus text-primary me-2"></i>New Message
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">To <span class="text-danger">*</span></label>
                            <select name="receiver_id" class="form-select @error('receiver_id') is-invalid @enderror" required>
                                <option value="">Select recipient</option>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient->id }}" {{ old('receiver_id') == $recipient->id ? 'selected' : '' }}>
                                        {{ $recipient->name }} 
                                        @if($recipient->role === 'hr_admin')
                                            (HR Admin)
                                        @elseif($recipient->role === 'admin_assistant')
                                            (Admin Assistant)
                                        @else
                                            ({{ $recipient->position ?? 'Employee' }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('receiver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="subject" 
                                class="form-control @error('subject') is-invalid @enderror" 
                                value="{{ old('subject') }}" 
                                placeholder="Enter message subject"
                                required
                            >
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea 
                                name="body" 
                                class="form-control @error('body') is-invalid @enderror" 
                                rows="10" 
                                placeholder="Type your message here..."
                                required
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Be clear and concise in your message</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('messages.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Message Guidelines -->
            <div class="card border-0 bg-light mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-info-circle text-primary me-2"></i>Message Guidelines
                    </h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Use a clear and descriptive subject line</li>
                        <li>Keep your message professional and courteous</li>
                        <li>Be specific about any requests or questions</li>
                        <li>Include relevant details (dates, names, reference numbers)</li>
                        <li>Proofread before sending</li>
                        <li>For urgent matters, consider following up with a phone call</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-people text-primary me-2"></i>Available Recipients
                    </h6>
                    
                    @if(auth()->user()->isStaff())
                        <p class="small text-muted">You can send messages to all employees.</p>
                    @else
                        <p class="small text-muted">You can send messages to HR staff.</p>
                    @endif

                    <div class="mt-3">
                        <small class="text-muted d-block mb-2">Total Recipients:</small>
                        <strong>{{ count($recipients) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Recent Conversations -->
            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3" style="font-weight: 600;">
                        <i class="bi bi-clock-history text-primary me-2"></i>Recent Conversations
                    </h6>
                    @php
                        $recentMessages = \App\Models\Message::where(function($q) {
                            $q->where('sender_id', auth()->id())
                              ->orWhere('receiver_id', auth()->id());
                        })
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                    @endphp
                    
                    @if($recentMessages->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentMessages as $msg)
                                @php
                                    $otherUser = $msg->sender_id == auth()->id() ? $msg->receiver : $msg->sender;
                                @endphp
                                <div class="list-group-item px-0 py-2 border-0">
                                    <small class="d-block fw-semibold">{{ $otherUser->name }}</small>
                                    <small class="text-muted">{{ Str::limit($msg->subject, 30) }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="small text-muted">No recent conversations</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
