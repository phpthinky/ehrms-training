<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TrainingTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
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
            'employee_number' => 'EMP-HRMO-001',  // Changed from employee_id
            'first_name' => 'Maria',
            'middle_name' => 'Santos',
            'last_name' => 'Reyes',
            'birth_date' => '1985-05-15',  // Added birth_date
            'gender' => 'female',
            'civil_status' => 'married',  // Added civil_status
            'address' => 'Sablayan, Occidental Mindoro',  // Added address
            'mobile_number' => '09171234567',  // Added mobile_number
            'email' => 'hradmin@sablayan.gov.ph',  // Added email
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
            'employee_number' => 'EMP-HRMO-002',  // Changed from employee_id
            'first_name' => 'Juan',
            'middle_name' => 'Cruz',
            'last_name' => 'Dela Cruz',
            'birth_date' => '1990-03-20',  // Added birth_date
            'gender' => 'male',
            'civil_status' => 'single',  // Added civil_status
            'address' => 'Sablayan, Occidental Mindoro',  // Added address
            'mobile_number' => '09187654321',  // Added mobile_number
            'email' => 'assistant@sablayan.gov.ph',  // Added email
            'position' => 'Admin Assistant V',
            'rank_level' => 'normal',
            'employment_type' => 'permanent',
            'date_hired' => '2018-06-01',
            'status' => 'active',
        ]);

        // Create Sample Employee 1
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
            'employee_number' => 'EMP-FIN-001',  // Changed from employee_id
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'birth_date' => '1992-07-10',  // Added birth_date
            'gender' => 'male',
            'civil_status' => 'married',  // Added civil_status
            'address' => 'Sablayan, Occidental Mindoro',  // Added address
            'mobile_number' => '09191234567',  // Added mobile_number
            'email' => 'johndoe@sablayan.gov.ph',  // Added email
            'position' => 'Administrative Officer II',
            'rank_level' => 'normal',
            'employment_type' => 'permanent',
            'date_hired' => '2020-03-10',
            'status' => 'active',
        ]);

        // Create Sample Employee 2
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
            'employee_number' => 'EMP-ENG-001',  // Changed from employee_id
            'first_name' => 'Jane',
            'middle_name' => 'Anne',
            'last_name' => 'Smith',
            'birth_date' => '1993-12-05',  // Added birth_date
            'gender' => 'female',
            'civil_status' => 'single',  // Added civil_status
            'address' => 'Sablayan, Occidental Mindoro',  // Added address
            'mobile_number' => '09201234567',  // Added mobile_number
            'email' => 'janesmith@sablayan.gov.ph',  // Added email
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
            ['title' => 'Leadership and Management', 'description' => 'Training on leadership and management', 'category' => 'leadership', 'rank_level' => 'higher', 'is_active' => true],
            ['title' => 'Gender and Development (GAD)', 'description' => 'Training on gender and development', 'category' => 'compliance', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Anti-Sexual Harassment', 'description' => 'Training on anti-sexual harassment', 'category' => 'compliance', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Records Management', 'description' => 'Training on records management', 'category' => 'technical', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Customer Service Excellence', 'description' => 'Training on customer service excellence', 'category' => 'soft_skills', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Microsoft Office Applications', 'description' => 'Training on Microsoft Office applications', 'category' => 'technical', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Financial Management', 'description' => 'Training on financial management', 'category' => 'technical', 'rank_level' => 'higher', 'is_active' => true],
            ['title' => 'Project Management', 'description' => 'Training on project management', 'category' => 'technical', 'rank_level' => 'higher', 'is_active' => true],
            ['title' => 'Communication Skills', 'description' => 'Training on communication skills', 'category' => 'soft_skills', 'rank_level' => 'all', 'is_active' => true],
            ['title' => 'Team Building', 'description' => 'Training on team building', 'category' => 'soft_skills', 'rank_level' => 'all', 'is_active' => true],
        ];

        foreach ($topics as $topic) {
            TrainingTopic::create($topic);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        $this->command->info('Login Credentials:');
        $this->command->info('HR Admin: hradmin@sablayan.gov.ph / password');
        $this->command->info('Admin Assistant: assistant@sablayan.gov.ph / password');
        $this->command->info('Employee 1: johndoe@sablayan.gov.ph / password');
        $this->command->info('Employee 2: janesmith@sablayan.gov.ph / password');
        $this->command->info('Guest: guest@sablayan.gov.ph / password');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('- Departments: 6');
        $this->command->info('- Users: 5');
        $this->command->info('- Employees: 4');
        $this->command->info('- Training Topics: 10');
    }
}
