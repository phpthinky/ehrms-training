<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of messages
     */
    public function index()
    {
        $messages = Message::where(function($query) {
                $query->where('receiver_id', auth()->id())
                      ->where('is_deleted_by_receiver', false);
            })
            ->orWhere(function($query) {
                $query->where('sender_id', auth()->id())
                      ->where('is_deleted_by_sender', false);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new message
     */
    public function create()
    {
        // Get HR staff if user is employee, get employees if user is staff
        if (auth()->user()->isStaff()) {
            $recipients = User::where('role', 'employee')
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            $recipients = User::whereIn('role', ['hr_admin', 'admin_assistant'])
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        return view('messages.create', compact('recipients'));
    }

    /**
     * Store a newly created message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:hr_users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $validated['sender_id'] = auth()->id();

        Message::create($validated);

        return redirect()->route('messages.index')
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Display the specified message
     */
    public function show(Message $message)
    {
        // Check if user is sender or receiver
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            abort(403);
        }

        // Mark as read if receiver
        if ($message->receiver_id === auth()->id() && !$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Remove the specified message
     */
    public function destroy(Message $message)
    {
        if ($message->sender_id === auth()->id()) {
            $message->update(['is_deleted_by_sender' => true]);
        } elseif ($message->receiver_id === auth()->id()) {
            $message->update(['is_deleted_by_receiver' => true]);
        } else {
            abort(403);
        }

        return redirect()->route('messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
