<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSettingsController extends Controller
{
    /**
     * Show the user's profile
     */
    public function profile()
    {
        $user = auth()->user();
        $employee = $user->employee;

        return view('profile-settings.profile', compact('user', 'employee'));
    }

    /**
     * Show account settings page
     */
    public function settings()
    {
        $user = auth()->user();
        $employee = $user->employee;

        return view('profile-settings.settings', compact('user', 'employee'));
    }

    /**
     * Update user's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update user's email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
            'current_password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        $user->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Email updated successfully.');
    }

    /**
     * Update user's name
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Name updated successfully.');
    }
}
