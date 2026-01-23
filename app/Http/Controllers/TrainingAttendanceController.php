<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Models\Employee;
use App\Models\EmployeeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingAttendanceController extends Controller
{
    /**
     * Add employee to training
     */
    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Check if already exists
        $exists = TrainingAttendance::where('training_id', $training->id)
            ->where('employee_id', $validated['employee_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Employee is already added to this training.');
        }

        // Check rank level eligibility
        $employee = Employee::find($validated['employee_id']);
        if ($training->rank_level !== 'all' && $employee->rank_level !== $training->rank_level) {
            return back()->with('error', 'Employee rank level does not match training requirements.');
        }

        TrainingAttendance::create([
            'training_id' => $training->id,
            'employee_id' => $validated['employee_id'],
            'attendance_status' => 'registered',
        ]);

        return back()->with('success', 'Employee added to training successfully.');
    }

    /**
     * Update attendance status
     */
    public function updateStatus(Request $request, TrainingAttendance $attendance)
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:registered,attended,absent,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        $attendance->update($validated);

        return back()->with('success', 'Attendance status updated successfully.');
    }

    /**
     * Upload certificate
     */
    public function uploadCertificate(Request $request, TrainingAttendance $attendance)
    {
        $validated = $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'certificate_number' => 'nullable|string|max:100',
            'certificate_date' => 'nullable|date',
        ]);

        // Store certificate file in public/uploads/certificates
        $file = $request->file('certificate');
        $fileName = time() . '_' . $attendance->employee->employee_number . '_' . $file->getClientOriginalName();
        
        // Store in public/uploads/certificates
        $file->move(public_path('uploads/certificates'), $fileName);
        $filePath = 'certificates/' . $fileName;

        // Get or create employee file record
        $employeeFile = EmployeeFile::create([
            'employee_id' => $attendance->employee_id,
            'file_name' => $fileName,
            'file_type' => 'training_certificate',
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'remarks' => 'Certificate for: ' . $attendance->training->title,
        ]);

        // Update attendance record
        $attendance->update([
            'employee_file_id' => $employeeFile->id,
            'certificate_uploaded' => true,
            'certificate_number' => $validated['certificate_number'] ?? null,
            'certificate_date' => $validated['certificate_date'] ?? now(),
        ]);

        return back()->with('success', 'Certificate uploaded successfully.');
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(TrainingAttendance $attendance)
    {
        if (!$attendance->certificate_uploaded || !$attendance->employee_file_id) {
            return back()->with('error', 'No certificate available for download.');
        }

        $file = EmployeeFile::find($attendance->employee_file_id);
        
        if (!$file) {
            return back()->with('error', 'Certificate file record not found.');
        }

        $filePath = public_path('uploads/' . $file->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Certificate file not found.');
        }

        return response()->download($filePath, $file->file_name);
    }

    /**
     * Bulk update attendance
     */
    public function bulkUpdate(Request $request, Training $training)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.id' => 'required|exists:training_attendance,id',
            'attendances.*.status' => 'required|in:attended,absent,pending',
        ]);

        foreach ($validated['attendances'] as $attendanceData) {
            TrainingAttendance::where('id', $attendanceData['id'])
                ->where('training_id', $training->id)
                ->update(['attendance_status' => $attendanceData['status']]);
        }

        return back()->with('success', 'Attendance updated for ' . count($validated['attendances']) . ' employees.');
    }

    /**
     * Mark all as attended
     */
    public function markAllAttended(Training $training)
    {
        $count = TrainingAttendance::where('training_id', $training->id)
            ->update(['attendance_status' => 'attended']);

        return back()->with('success', "Marked {$count} employees as attended.");
    }

    /**
     * Remove attendee from training
     */
    public function destroy(TrainingAttendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendee removed from training.');
    }

    /**
     * Get eligible employees for training
     */
    public function getEligibleEmployees(Training $training)
    {
        $query = Employee::where('status', 'active');

        // Filter by rank level if specified
        if ($training->rank_level !== 'all') {
            $query->where('rank_level', $training->rank_level);
        }

        // Exclude already added employees
        $alreadyAdded = TrainingAttendance::where('training_id', $training->id)
            ->pluck('employee_id');

        $employees = $query->whereNotIn('id', $alreadyAdded)
            ->with('department')
            ->orderBy('last_name')
            ->get();

        return response()->json($employees);
    }

    /**
     * Export attendance sheet
     */
    public function exportAttendance(Training $training)
    {
        // This will be implemented with Excel export
        // For now, return a simple response
        return back()->with('info', 'Export feature coming soon.');
    }

    /**
     * Send attendance notifications
     */
    public function sendNotifications(Training $training)
    {
        $attendances = TrainingAttendance::where('training_id', $training->id)
            ->with('employee.user')
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->employee->user) {
                // Create notification
                \App\Models\Notification::create([
                    'user_id' => $attendance->employee->user->id,
                    'type' => 'training_reminder',
                    'title' => 'Training Reminder',
                    'message' => "You are registered for: {$training->title} on " . 
                                \Carbon\Carbon::parse($training->start_date)->format('F d, Y'),
                ]);
            }
        }

        return back()->with('success', 'Notifications sent to ' . $attendances->count() . ' attendees.');
    }
}
