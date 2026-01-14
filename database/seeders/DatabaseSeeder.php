<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TrainingTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Departments
        $departments = [
            ['name' => 'Human Resource Management Office', 'code' => 'HRMO', 'description' => 'Handles employee records and training'],
            ['name' => 'Finance Department', 'code' => 'FIN', 'description' => 'Manages financial operations'],
            ['name' => 'Engineering Department', 'code' => 'ENG', 'description' => 'Infrastructure and engineering'],
            ['name' => 'Health Department', 'code' => 'HEALTH', 'description' => 'Public health services'],
            ['name' => 'Education Department', 'code' => 'EDU', 'description' => 'Educational programs'],
            ['name' => 'Agriculture Department', 'code' => 'AGRI', 'description' => 'Agricultural development'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create HR Admin
        $hrAdmin = User::create([
            'name' => 'HR Administrator',
            'email' => 'hradmin@sablayan.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'hr_admin',
            'status' => 'active',
            'employee_id' => 'EMP-HRMO-001',
            'department' => 'HRMO',
            'position' => 'HRMO III',
            'employment_type' => 'permanent',
        ]);

        Employee::create([
            'user_id' => $hrAdmin->id,
            'department_id' => 1,
            'employee_number' => 'EMP-HRMO-001',
            'first_name' => 'Maria',
            'middle_name' => 'Santos',
            'last_name' => 'Reyes',
            'gender' => 'female',
            'position' => 'HRMO III',
            'rank_level' => 'higher',
            'employment_type' => 'permanent',
            'date_hired' => '2015-01-15',
            'status' => 'active',
        ]);

        // Create Admin Assistant
        $adminAssistant = User::create([
            'name' => 'Admin Assistant',
            'email' => 'assistant@sablayan.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'admin_assistant',
            'status' => 'active',
            'employee_id' => 'EMP-HRMO-002',
            'department' => 'HRMO',
            'position' => 'Admin Assistant V',
            'employment_type' => 'permanent',
        ]);

        Employee::create([
            'user_id' => $adminAssistant->id,
            'department_id' => 1,
            'employee_number' => 'EMP-HRMO-002',
            'first_name' => 'Juan',
            'middle_name' => 'Cruz',
            'last_name' => 'Dela Cruz',
            'gender' => 'male',
            'position' => 'Admin Assistant V',
            'rank_level' => 'normal',
            'employment_type' => 'permanent',
            'date_hired' => '2018-06-01',
            'status' => 'active',
        ]);

        // Create Sample Employees
        $employeeUser1 = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@sablayan.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'employee_id' => 'EMP-FIN-001',
            'department' => 'Finance',
            'position' => 'Administrative Officer II',
            'employment_type' => 'permanent',
        ]);

        Employee::create([
            'user_id' => $employeeUser1->id,
            'department_id' => 2,
            'employee_number' => 'EMP-FIN-001',
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'gender' => 'male',
            'position' => 'Administrative Officer II',
            'rank_level' => 'normal',
            'employment_type' => 'permanent',
            'date_hired' => '2020-03-10',
            'status' => 'active',
        ]);

        $employeeUser2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'janesmith@sablayan.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'employee_id' => 'EMP-ENG-001',
            'department' => 'Engineering',
            'position' => 'Engineer II',
            'employment_type' => 'job_order',
        ]);

        Employee::create([
            'user_id' => $employeeUser2->id,
            'department_id' => 3,
            'employee_number' => 'EMP-ENG-001',
            'first_name' => 'Jane',
            'middle_name' => 'Anne',
            'last_name' => 'Smith',
            'gender' => 'female',
            'position' => 'Engineer II',
            'rank_level' => 'normal',
            'employment_type' => 'job_order',
            'date_hired' => '2021-08-15',
            'status' => 'active',
        ]);

        // Create Guest Account
        User::create([
            'name' => 'Guest User',
            'email' => 'guest@sablayan.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'guest',
            'status' => 'active',
        ]);

        // Create Training Topics
        $topics = [
            ['title' => 'Leadership and Management', 'category' => 'leadership', 'rank_level' => 'higher'],
            ['title' => 'Gender and Development (GAD)', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Anti-Sexual Harassment', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Records Management', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Customer Service Excellence', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Microsoft Office Applications', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Financial Management', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Project Management', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Communication Skills', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Team Building', 'category' => 'soft_skills', 'rank_level' => 'all'],
        ];

        foreach ($topics as $topic) {
            TrainingTopic::create($topic);
        }

        $this->command->info('✅ Database seeded successfully with hr_ prefixed tables!');
        $this->command->newLine();
        $this->command->info('Login Credentials:');
        $this->command->info('HR Admin: hradmin@sablayan.gov.ph / password');
        $this->command->info('Admin Assistant: assistant@sablayan.gov.ph / password');
        $this->command->info('Employee 1: johndoe@sablayan.gov.ph / password');
        $this->command->info('Employee 2: janesmith@sablayan.gov.ph / password');
        $this->command->info('Guest: guest@sablayan.gov.ph / password');
        $this->command->newLine();
        $this->command->info('📊 Tables created with hr_ prefix:');
        $this->command->info('- hr_departments');
        $this->command->info('- hr_employees');
        $this->command->info('- hr_employee_files');
        $this->command->info('- hr_training_topics');
        $this->command->info('- hr_trainings');
        $this->command->info('- hr_training_attendance');
        $this->command->info('- hr_training_surveys');
        $this->command->info('- hr_messages');
        $this->command->info('- hr_notifications');
        $this->command->info('- hr_public_files');
    }
}
