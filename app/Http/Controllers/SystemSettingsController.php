<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    /**
     * Show 201 files settings page
     */
    public function fileSettings()
    {
        // Only HR Admin can access
        if (!auth()->user()->isHRAdmin()) {
            abort(403, 'Unauthorized access. Only HR Admin can manage settings.');
        }

        $settings = SystemSetting::where('category', '201_files')->get();

        return view('settings.file-settings', compact('settings'));
    }

    /**
     * Update 201 files settings
     */
    public function updateFileSettings(Request $request)
    {
        // Only HR Admin can update
        if (!auth()->user()->isHRAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'allow_employee_file_upload' => 'required|in:0,1',
        ]);

        SystemSetting::set('allow_employee_file_upload', $validated['allow_employee_file_upload']);

        $status = $validated['allow_employee_file_upload'] == '1' ? 'enabled' : 'disabled';

        return back()->with('success', "Employee file upload has been {$status} successfully.");
    }
}
