<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeFile;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class EmployeeFileController extends Controller
{
    /**
     * Display employee's 201 files
     */
    public function index($employeeId)
    {
        $employee = Employee::with(['files' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'user', 'department'])->findOrFail($employeeId);

        // Check authorization - HR staff can view all, employees can view only their own
        $user = auth()->user();
        
        if (!$user->isStaff()) {
            // For employees, check if this is their own record
            if (!$user->employee || $user->employee->id != $employee->id) {
                abort(403, 'Unauthorized access');
            }
        }

        // Group files by type
        $filesByType = $employee->files->groupBy('file_type');

        return view('employee-files.index', compact('employee', 'filesByType'));
    }

    /**
     * Show upload form
     */
    public function create($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $user = auth()->user();

        // Check permissions
        $allowEmployeeUpload = SystemSetting::allowEmployeeFileUpload();

        if (!$user->isStaff()) {
            // For employees, check if:
            // 1. They own this record
            // 2. Employee upload is enabled
            if (!$user->employee || $user->employee->id != $employee->id) {
                abort(403, 'Unauthorized access');
            }

            if (!$allowEmployeeUpload) {
                abort(403, 'Employee file upload is currently disabled. Please contact HR to upload documents.');
            }
        }

        return view('employee-files.create', compact('employee'));
    }

    /**
     * Store uploaded file
     */
    public function store(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $user = auth()->user();

        // Check permissions
        $allowEmployeeUpload = SystemSetting::allowEmployeeFileUpload();

        if (!$user->isStaff()) {
            // For employees, verify ownership and setting
            if (!$user->employee || $user->employee->id != $employee->id) {
                abort(403, 'Unauthorized access');
            }

            if (!$allowEmployeeUpload) {
                return back()->with('error', 'Employee file upload is currently disabled. Please contact HR.');
            }
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB
            'file_type' => 'required|in:pds,tor,certificate,diploma,nbi_clearance,medical_certificate,tax_identification,birth_certificate,marriage_certificate,service_record,other',
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        
        // Generate filename
        $fileType = $validated['file_type'];
        $fileName = time() . '_' . $employee->employee_number . '_' . $fileType . '.' . $file->getClientOriginalExtension();
        
        // Store in public/uploads/employee_files/{employee_number}/
        $employeeFolder = 'employee_files/' . $employee->employee_number;
        $fullPath = public_path('uploads/' . $employeeFolder);
        
        // Create directory if not exists
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        $file->move($fullPath, $fileName);
        $filePath = $employeeFolder . '/' . $fileName;

        // Save to database
        EmployeeFile::create([
            'employee_id' => $employee->id,
            'file_name' => $fileName,
            'file_type' => $validated['file_type'],
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'remarks' => $validated['description'] ?? null,
        ]);

        return redirect()->route('employee-files.index', $employee->id)
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Download file
     */
    public function download($fileId)
    {
        $file = EmployeeFile::with('employee')->findOrFail($fileId);
        
        // Check authorization - HR staff can download all, employees can download only their own
        $user = auth()->user();
        
        if (!$user->isStaff()) {
            // For employees, check if this is their own file
            if (!$user->employee || $user->employee->id != $file->employee_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $filePath = public_path('uploads/' . $file->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $file->file_name);
    }

    /**
     * Delete file
     */
    public function destroy($fileId)
    {
        $file = EmployeeFile::findOrFail($fileId);
        
        // Only HR staff can delete
        if (!auth()->user()->isStaff()) {
            abort(403, 'Only HR staff can delete files');
        }

        $filePath = public_path('uploads/' . $file->file_path);
        
        // Delete physical file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    /**
     * Employee's own files view
     */
    public function myFiles()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        return redirect()->route('employee-files.index', $employee->id);
    }
}
