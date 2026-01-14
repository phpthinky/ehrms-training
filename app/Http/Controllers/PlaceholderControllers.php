<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicFileController extends Controller
{
    public function index()
    {
        return view('public-files.index');
    }

    public function create()
    {
        return view('public-files.create');
    }

    public function store(Request $request)
    {
        return back()->with('info', 'File upload feature coming soon.');
    }

    public function show($id)
    {
        return back();
    }

    public function edit($id)
    {
        return back();
    }

    public function update(Request $request, $id)
    {
        return back();
    }

    public function destroy($id)
    {
        return back();
    }
}

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        return back()->with('info', 'Profile update feature coming soon.');
    }
}

class SettingsController extends Controller
{
    public function show()
    {
        return view('settings.show');
    }
}

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function trainings()
    {
        return view('reports.trainings');
    }

    public function employees()
    {
        return view('reports.employees');
    }
}
