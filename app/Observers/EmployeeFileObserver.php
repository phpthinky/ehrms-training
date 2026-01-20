<?php

namespace App\Observers;

use App\Models\EmployeeFile;
use App\Models\Notification;
use App\Models\User;

class EmployeeFileObserver
{
    /**
     * Handle the EmployeeFile "created" event.
     */
    public function created(EmployeeFile $employeeFile): void
    {
        // Notify all HR Admins and Admin Assistants when an employee uploads a document
        $staffUsers = User::whereIn('role', ['hr_admin', 'admin_assistant'])->get();

        foreach ($staffUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'document_uploaded',
                'title' => 'New Document Uploaded',
                'message' => "Employee {$employeeFile->employee->first_name} {$employeeFile->employee->last_name} uploaded a new document: {$employeeFile->file_type}",
                'related_id' => $employeeFile->id,
                'related_type' => 'App\Models\EmployeeFile',
                'data' => json_encode([
                    'employee_id' => $employeeFile->employee_id,
                    'employee_name' => $employeeFile->employee->first_name . ' ' . $employeeFile->employee->last_name,
                    'file_type' => $employeeFile->file_type,
                    'file_name' => $employeeFile->file_name,
                ]),
                'is_read' => false,
            ]);
        }
    }
}
