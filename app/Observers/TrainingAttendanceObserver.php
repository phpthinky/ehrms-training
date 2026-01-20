<?php

namespace App\Observers;

use App\Models\TrainingAttendance;
use App\Models\Notification;

class TrainingAttendanceObserver
{
    /**
     * Handle the TrainingAttendance "created" event.
     * Notify employee when they are registered for a training
     */
    public function created(TrainingAttendance $attendance): void
    {
        // Only notify if employee has a user account
        if ($attendance->employee && $attendance->employee->user_id) {
            Notification::create([
                'user_id' => $attendance->employee->user_id,
                'type' => 'training_registered',
                'title' => 'You\'re Registered for Training!',
                'message' => "You have been registered for the training: {$attendance->training->title} scheduled on " . date('M d, Y', strtotime($attendance->training->start_date)),
                'related_id' => $attendance->training_id,
                'related_type' => 'App\Models\Training',
                'data' => json_encode([
                    'training_id' => $attendance->training_id,
                    'training_title' => $attendance->training->title,
                    'start_date' => $attendance->training->start_date,
                    'start_time' => $attendance->training->start_time,
                    'venue' => $attendance->training->venue,
                    'attendance_id' => $attendance->id,
                ]),
                'is_read' => false,
            ]);
        }
    }

    /**
     * Handle the TrainingAttendance "updated" event.
     * Notify employee when their attendance status changes
     */
    public function updated(TrainingAttendance $attendance): void
    {
        // Only notify if attendance status changed and employee has user account
        if ($attendance->wasChanged('attendance_status') && $attendance->employee && $attendance->employee->user_id) {
            $statusMessages = [
                'attended' => 'Your attendance has been confirmed for',
                'absent' => 'You were marked absent from',
                'registered' => 'Your registration is confirmed for',
            ];

            $message = ($statusMessages[$attendance->attendance_status] ?? 'Your status has been updated for')
                     . " the training: {$attendance->training->title}";

            Notification::create([
                'user_id' => $attendance->employee->user_id,
                'type' => 'attendance_updated',
                'title' => 'Training Attendance Updated',
                'message' => $message,
                'related_id' => $attendance->training_id,
                'related_type' => 'App\Models\Training',
                'data' => json_encode([
                    'training_id' => $attendance->training_id,
                    'training_title' => $attendance->training->title,
                    'attendance_status' => $attendance->attendance_status,
                    'attendance_id' => $attendance->id,
                ]),
                'is_read' => false,
            ]);
        }
    }
}
