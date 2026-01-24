<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['user', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_number' => 'required|string|unique:employees,employee_number',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'employment_type' => 'required|in:permanent,job_order,contract',
            'rank_level' => 'required|in:higher,normal',
            'date_hired' => 'nullable|date',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make('password'), // Default password
            'role' => 'employee',
            'status' => 'active',
            'employee_id' => $validated['employee_number'],
            'position' => $validated['position'],
            'employment_type' => $validated['employment_type'],
        ]);

        // Create employee record
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'employee_number' => $validated['employee_number'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'position' => $validated['position'],
            'rank_level' => $validated['rank_level'],
            'employment_type' => $validated['employment_type'],
            'date_hired' => $validated['date_hired'],
            'status' => 'active',
            'email' => $validated['email'],
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully. Default password is "password".');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'files', 'trainings']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'employment_type' => 'required|in:permanent,job_order,contract',
            'rank_level' => 'required|in:higher,normal',
            'status' => 'required|in:active,inactive,resigned',
        ]);

        $employee->update($validated);

        // Update user record
        $employee->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'position' => $validated['position'],
            'employment_type' => $validated['employment_type'],
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Show employee's profile (for employees viewing their own)
     */
    public function myProfile()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Employee profile not found.');
        }

        $employee->load(['department', 'files', 'trainings.training.topic']);
        return view('employees.profile', compact('employee'));
    }

    /**
     * Employee's training history
     */
    public function myTrainings()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        $trainings = $employee->trainings()
            ->with(['training.topic'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employees.my-trainings', compact('employee', 'trainings'));
    }

    /**
     * View employee's trainings attended (HR Admin view)
     */
    public function trainings(Employee $employee)
    {
        // Check authorization - HR staff can view all, employees can view only their own
        $user = auth()->user();

        if (!$user->isStaff()) {
            // For employees, check if this is their own record
            if (!$user->employee || $user->employee->id != $employee->id) {
                abort(403, 'Unauthorized access');
            }
        }

        $employee->load(['user', 'department']);

        $trainings = $employee->trainings()
            ->with(['training.topic'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employees.trainings', compact('employee', 'trainings'));
    }
}
