<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingTopic;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings
     */
    public function index()
    {
        $trainings = Training::with(['topic', 'creator'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new training
     */
    public function create()
    {
        $topics = TrainingTopic::where('is_active', true)->get();
        return view('trainings.create', compact('topics'));
    }

    /**
     * Store a newly created training
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:internal,external',
            'training_topic_id' => 'nullable|exists:hr_training_topics,id',
            'venue' => 'nullable|string|max:255',
            'facilitator' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'rank_level' => 'required|in:all,higher,normal',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'scheduled';
        
        if ($validated['type'] === 'external') {
            $validated['requested_by'] = auth()->id();
        }

        Training::create($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training created successfully.');
    }

    /**
     * Display the specified training
     */
    public function show(Training $training)
    {
        $training->load(['topic', 'creator', 'attendances.employee']);
        return view('trainings.show', compact('training'));
    }

    /**
     * Show the form for editing the specified training
     */
    public function edit(Training $training)
    {
        $topics = TrainingTopic::where('is_active', true)->get();
        return view('trainings.edit', compact('training', 'topics'));
    }

    /**
     * Update the specified training
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'facilitator' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $training->update($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training updated successfully.');
    }

    /**
     * Remove the specified training
     */
    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    /**
     * Show my trainings (for employees)
     */
    public function myTrainings()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Employee profile not found.');
        }

        $trainings = $employee->trainings()
            ->with('training')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('trainings.my-trainings', compact('trainings'));
    }
}
