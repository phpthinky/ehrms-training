<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSurveyController extends Controller
{
    public function show()
    {
        // TODO: Implement survey form
        return view('surveys.form');
    }

    public function submit(Request $request)
    {
        // TODO: Implement survey submission
        return back()->with('info', 'Survey feature coming soon.');
    }
}
