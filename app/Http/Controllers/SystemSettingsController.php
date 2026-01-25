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

    /**
     * Show general settings page
     */
    public function generalSettings()
    {
        // Only HR Admin can access
        if (!auth()->user()->isHRAdmin()) {
            abort(403, 'Unauthorized access. Only HR Admin can manage settings.');
        }

        $settings = SystemSetting::where('category', 'general')->get();

        return view('settings.general-settings', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request)
    {
        // Only HR Admin can update
        if (!auth()->user()->isHRAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_short_name' => 'required|string|max:50',
            'app_tagline' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            SystemSetting::set('app_logo', 'uploads/settings/' . $filename);
        }

        // Update other settings
        SystemSetting::set('app_name', $validated['app_name']);
        SystemSetting::set('app_short_name', $validated['app_short_name']);
        SystemSetting::set('app_tagline', $validated['app_tagline'] ?? '');
        SystemSetting::set('organization_name', $validated['organization_name']);

        return back()->with('success', 'General settings updated successfully.');
    }
}
