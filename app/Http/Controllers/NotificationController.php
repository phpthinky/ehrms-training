<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Create a notification for a user
     */
    public static function create($userId, $type, $title, $message, $relatedId = null, $relatedType = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'is_read' => false,
        ]);
    }

    /**
     * Create bulk notifications
     */
    public static function createBulk($userIds, $type, $title, $message, $relatedId = null, $relatedType = null)
    {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'related_id' => $relatedId,
                'related_type' => $relatedType,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }

        return count($notifications);
    }

    /**
     * Notify about new training
     */
    public static function notifyTrainingCreated($training)
    {
        // Get all eligible employees based on rank level
        $query = \App\Models\Employee::where('status', 'active');
        
        if ($training->rank_level !== 'all') {
            $query->where('rank_level', $training->rank_level);
        }
        
        $employees = $query->get();
        $userIds = $employees->pluck('user_id')->filter()->toArray();

        if (empty($userIds)) {
            return 0;
        }

        return self::createBulk(
            $userIds,
            'training',
            'New Training Available: ' . $training->title,
            'A new training has been scheduled from ' . date('M d', strtotime($training->start_date)) . 
            ' to ' . date('M d, Y', strtotime($training->end_date)) . '. Topic: ' . ($training->topic->title ?? 'General'),
            $training->id,
            'training'
        );
    }

    /**
     * Notify employee about enrollment
     */
    public static function notifyEnrolled($attendance)
    {
        $training = $attendance->training;
        $employee = $attendance->employee;

        if (!$employee->user_id) {
            return false;
        }

        return self::create(
            $employee->user_id,
            'enrollment',
            'Enrolled in Training: ' . $training->title,
            'You have been enrolled in the training "' . $training->title . '" scheduled for ' . 
            date('M d, Y', strtotime($training->start_date)) . '.',
            $attendance->id,
            'attendance'
        );
    }

    /**
     * Notify about attendance status update
     */
    public static function notifyAttendanceUpdated($attendance)
    {
        $employee = $attendance->employee;

        if (!$employee->user_id) {
            return false;
        }

        $statusMessages = [
            'attended' => 'Your attendance has been marked as ATTENDED.',
            'absent' => 'You were marked as ABSENT for this training.',
            'excused' => 'Your absence has been marked as EXCUSED.',
            'registered' => 'You are REGISTERED for this training.',
        ];

        $message = $statusMessages[$attendance->attendance_status] ?? 'Your attendance status has been updated.';

        return self::create(
            $employee->user_id,
            'attendance',
            'Attendance Updated: ' . $attendance->training->title,
            $message,
            $attendance->id,
            'attendance'
        );
    }

    /**
     * Notify about certificate upload
     */
    public static function notifyCertificateUploaded($attendance)
    {
        $employee = $attendance->employee;

        if (!$employee->user_id) {
            return false;
        }

        return self::create(
            $employee->user_id,
            'certificate',
            'Certificate Available: ' . $attendance->training->title,
            'Your training certificate for "' . $attendance->training->title . '" is now available for download.',
            $attendance->id,
            'attendance'
        );
    }

    /**
     * Notify all active employees about survey
     */
    public static function notifySurveyDue($year)
    {
        $employees = \App\Models\Employee::where('status', 'active')->get();
        $userIds = $employees->pluck('user_id')->filter()->toArray();

        if (empty($userIds)) {
            return 0;
        }

        return self::createBulk(
            $userIds,
            'survey',
            'Training Needs Survey ' . $year,
            'Please complete the annual Training Needs Survey to help us plan trainings that meet your professional development needs.',
            null,
            'survey'
        );
    }
}
