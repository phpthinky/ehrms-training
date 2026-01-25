<?php

namespace App\Http\Controllers;

use App\Models\TrainingTopic;
use Illuminate\Http\Request;

class TrainingTopicController extends Controller
{
    /**
     * Display a listing of training topics
     */
    public function index()
    {
        $topics = TrainingTopic::withCount('trainings')
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(20);

        return view('training-topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new training topic
     */
    public function create()
    {
        return view('training-topics.create');
    }

    /**
     * Store a newly created training topic
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:training_topics,title',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:technical,soft_skills,compliance,leadership,other',
            'rank_level' => 'required|in:all,higher,normal',
            'is_active' => 'boolean',
        ]);

        TrainingTopic::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'rank_level' => $validated['rank_level'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('training-topics.index')
            ->with('success', 'Training topic created successfully.');
    }

    /**
     * Display the specified training topic
     */
    public function show(TrainingTopic $trainingTopic)
    {
        $trainingTopic->loadCount('trainings');
        $trainingTopic->load(['trainings' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('training-topics.show', compact('trainingTopic'));
    }

    /**
     * Show the form for editing the training topic
     */
    public function edit(TrainingTopic $trainingTopic)
    {
        return view('training-topics.edit', compact('trainingTopic'));
    }

    /**
     * Update the specified training topic
     */
    public function update(Request $request, TrainingTopic $trainingTopic)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:training_topics,title,' . $trainingTopic->id,
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:technical,soft_skills,compliance,leadership,other',
            'rank_level' => 'required|in:all,higher,normal',
            'is_active' => 'boolean',
        ]);

        $trainingTopic->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'rank_level' => $validated['rank_level'],
            'is_active' => $request->is_active ?? $trainingTopic->is_active,
        ]);

        return redirect()->route('training-topics.index')
            ->with('success', 'Training topic updated successfully.');
    }

    /**
     * Remove the specified training topic
     */
    public function destroy(TrainingTopic $trainingTopic)
    {
        // Check if topic has trainings
        if ($trainingTopic->trainings()->count() > 0) {
            return redirect()->route('training-topics.index')
                ->with('error', 'Cannot delete topic with existing trainings. Consider deactivating it instead.');
        }

        $trainingTopic->delete();

        return redirect()->route('training-topics.index')
            ->with('success', 'Training topic deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(TrainingTopic $trainingTopic)
    {
        $trainingTopic->update([
            'is_active' => !$trainingTopic->is_active
        ]);

        $status = $trainingTopic->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Training topic {$status} successfully.");
    }
}
