<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\Notification;
use App\Models\User;
use App\Models\Employee;

class TrainingObserver
{
    /**
     * Handle the Training "created" event.
     * Notify HR staff when new training is created
     */
    public function created(Training $training): void
    {
        // Notify all HR Admins and Admin Assistants
        $staffUsers = User::whereIn('role', ['hr_admin', 'admin_assistant'])->get();

        foreach ($staffUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'training_created',
                'title' => 'New Training Created',
                'message' => "A new training has been scheduled: {$training->title}",
                'related_id' => $training->id,
                'related_type' => 'App\Models\Training',
                'data' => json_encode([
                    'training_id' => $training->id,
                    'training_title' => $training->title,
                    'start_date' => $training->start_date,
                    'type' => $training->type,
                ]),
                'is_read' => false,
            ]);
        }
    }

    /**
     * Handle the Training "updated" event.
     * Notify registered participants when training details change
     */
    public function updated(Training $training): void
    {
        // Only notify if training has been updated and is scheduled
        if ($training->status === 'scheduled' && $training->attendances()->count() > 0) {
            // Get all registered employees
            $employeeIds = $training->attendances()->pluck('employee_id')->unique();
            $userIds = Employee::whereIn('id', $employeeIds)
                ->whereNotNull('user_id')
                ->pluck('user_id');

            foreach ($userIds as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'training_updated',
                    'title' => 'Training Schedule Updated',
                    'message' => "The training '{$training->title}' has been updated. Please check the new details.",
                    'related_id' => $training->id,
                    'related_type' => 'App\Models\Training',
                    'data' => json_encode([
                        'training_id' => $training->id,
                        'training_title' => $training->title,
                        'start_date' => $training->start_date,
                        'venue' => $training->venue,
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}
