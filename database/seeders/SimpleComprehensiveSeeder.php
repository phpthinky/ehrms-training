<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TrainingTopic;
use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Models\TrainingSurvey;
use App\Models\EmployeeFile;
use App\Models\HRDocument;
use App\Models\Notification;
use Carbon\Carbon;

class SimpleComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds - 2 years of comprehensive demo data
     */
    public function run(): void
    {
        echo "🌱 Starting LGU Sablayan EHRMS Database Seeding...\n\n";

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data in proper order (children first, parents last)
        $this->clearData();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Departments
        echo "📁 Creating Departments...\n";
        $departments = $this->createDepartments();

        // 2. Create Employees (50 employees across departments)
        echo "👥 Creating 50 Employees...\n";
        $employees = $this->createEmployees($departments);

        // 3. Create Training Topics (30 topics)
        echo "📚 Creating 30 Training Topics...\n";
        $topics = $this->createTrainingTopics();

        // 4. Create Trainings (40 trainings over 2 years)
        echo "🎓 Creating 40 Trainings (2023-2024)...\n";
        $trainings = $this->createTrainings($topics);

        // 5. Create Training Attendance (realistic attendance)
        echo "✅ Creating Training Attendance Records...\n";
        $this->createTrainingAttendance($trainings, $employees);

        // 6. Create Training Surveys (2023 & 2024)
        echo "📋 Creating Training Surveys (2 years)...\n";
        $this->createTrainingSurveys($employees, $topics);

        // 7. Create Employee Files (201 files)
        echo "📄 Creating Employee 201 Files...\n";
        $this->createEmployeeFiles($employees);

        // 8. Create HR Documents
        echo "🗂️ Creating HR Documents...\n";
        $this->createHRDocuments();

        // 9. Create Notifications
        echo "🔔 Creating Notifications...\n";
        $this->createNotifications($employees, $trainings);

        echo "\n✅ Seeding Complete!\n";
        echo "📊 Summary:\n";
        echo "   - Departments: " . Department::count() . "\n";
        echo "   - Users: " . User::count() . "\n";
        echo "   - Employees: " . Employee::count() . "\n";
        echo "   - Training Topics: " . TrainingTopic::count() . "\n";
        echo "   - Trainings: " . Training::count() . "\n";
        echo "   - Attendance Records: " . TrainingAttendance::count() . "\n";
        echo "   - Surveys: " . TrainingSurvey::count() . "\n";
        echo "   - Employee Files: " . EmployeeFile::count() . "\n";
        echo "   - HR Documents: " . HRDocument::count() . "\n";
        echo "   - Notifications: " . Notification::count() . "\n";
        echo "\n🎉 Database ready for demo!\n\n";
        echo "🔑 Login Credentials:\n";
        echo "   HR Admin: admin@sablayan.gov.ph / password\n";
        echo "   Admin Assistant: assistant@sablayan.gov.ph / password\n";
        echo "   Guest: guest@sablayan.gov.ph / password\n";
        echo "   (+ 50 employees with password: password)\n\n";
    }

    private function clearData()
    {
        echo "🗑️  Clearing existing data...\n";
        
        // Use Model::query()->delete() instead of Model::delete()
        // This properly uses the configured table names with prefix
        
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Delete in proper order (children first, then parents)
            // DB::table() automatically uses the hr_ prefix from config
            DB::table('notifications')->delete();
            DB::table('documents')->delete();
            DB::table('employee_files')->delete();
            DB::table('training_surveys')->delete();
            DB::table('training_attendance')->delete();
            DB::table('trainings')->delete();
            DB::table('training_topics')->delete();
            DB::table('employees')->delete();
            DB::table('users')->where('email', '!=', 'admin@sablayan.gov.ph')->delete();
            DB::table('departments')->delete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            echo "⚠️  Note: Some tables may not exist yet (first run). Continuing...\n";
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        
        echo "✅ Existing data cleared.\n\n";
    }

    private function createDepartments()
    {
        $depts = [
            'Office of the Municipal Mayor',
            'Office of the Vice Mayor',
            'Sangguniang Bayan',
            'Municipal Planning and Development Office',
            'Municipal Budget Office',
            'Municipal Treasurer\'s Office',
            'Municipal Assessor\'s Office',
            'Municipal Accountant\'s Office',
            'Municipal Civil Registrar',
            'Municipal Health Office',
            'Municipal Social Welfare and Development Office',
            'Municipal Agriculture Office',
            'Municipal Engineering Office',
            'Municipal Environment and Natural Resources Office',
            'General Services Office',
            'Human Resource Management Office',
            'Municipal Disaster Risk Reduction and Management Office',
            'Business Permits and Licensing Office',
        ];

        $departments = [];
        foreach ($depts as $index => $name) {
            $code = $this->generateDepartmentCode($name, $index);
            $departments[] = Department::create([
                'name' => $name,
                'code' => $code,
                'description' => 'Responsible for ' . strtolower($name) . ' operations and services.',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now()->subYears(2),
            ]);
        }

        return collect($departments);
    }

    private function generateDepartmentCode($name, $index)
    {
        // Generate unique codes
        $codes = [
            'MAYOR', 'VMYR', 'SANGGU', 'MPDO', 'MBO', 'MTO',
            'MAO', 'MACCO', 'MCR', 'MHO', 'MSWDO', 'MAGRO',
            'MEO', 'MENRO', 'GSO', 'HRMO', 'MDRRMO', 'BPLO'
        ];
        return $codes[$index] ?? 'DEPT' . ($index + 1);
    }

    private function createEmployees($departments)
    {
        $employees = [];
        
        // Create admin user first
        $adminUser = User::firstOrCreate(
            ['email' => 'hradmin@sablayan.gov.ph'],
            [
                'name' => 'Maria Santos',
                'password' => Hash::make('password'),
                'role' => 'hr_admin',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
            ]
        );

        $adminEmployee = Employee::updateOrCreate(
            ['employee_number' => 'EMP-2023-001'],
            [
                'user_id' => $adminUser->id,
                'employee_number' => 'EMP-2023-001',
                'first_name' => 'Maria',
                'middle_name' => 'Cruz',
                'last_name' => 'Santos',
                'suffix' => null,
                'birth_date' => Carbon::parse('1985-05-15'),
                'gender' => 'female',
                'civil_status' => 'married',
                'address' => 'Barangay Poblacion, Sablayan, Occidental Mindoro',
                'mobile_number' => '09171234567',
                'email' => 'admin@sablayan.gov.ph',
                'department_id' => $departments->firstWhere('code', 'HRMO')->id,
                'position' => 'HR Manager',
                'employment_type' => 'permanent',
                'date_hired' => Carbon::now()->subYears(10),
                'rank_level' => 'higher',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
            ]
        );
        $employees[] = $adminEmployee;

        // Create Admin Assistant
        $assistantUser = User::firstOrCreate(
            ['email' => 'assistant@sablayan.gov.ph'],
            [
                'name' => 'Admin Assistant',
                'password' => Hash::make('password'),
                'role' => 'admin_assistant',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
            ]
        );

        $assistantEmployee = Employee::updateOrCreate(
            ['employee_number' => 'EMP-2023-002'],
            [
                'user_id' => $assistantUser->id,
                'employee_number' => 'EMP-2023-002',
                'first_name' => 'Juan',
                'middle_name' => 'Cruz',
                'last_name' => 'Dela Cruz',
                'suffix' => null,
                'birth_date' => Carbon::parse('1990-03-20'),
                'gender' => 'male',
                'civil_status' => 'single',
                'address' => 'Barangay Poblacion, Sablayan, Occidental Mindoro',
                'mobile_number' => '09187654321',
                'email' => 'assistant@sablayan.gov.ph',
                'department_id' => $departments->firstWhere('code', 'HRMO')->id,
                'position' => 'Admin Assistant V',
                'employment_type' => 'permanent',
                'date_hired' => Carbon::now()->subYears(5),
                'rank_level' => 'normal',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(2),
            ]
        );
        $employees[] = $assistantEmployee;

        // Create Guest User (no employee record)
        User::firstOrCreate(
            ['email' => 'guest@sablayan.gov.ph'],
            [
                'name' => 'Guest User',
                'password' => Hash::make('password'),
                'role' => 'guest',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(1),
            ]
        );

        // Generate 49 more employees (all with user accounts)
        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Carlos', 'Elena', 'Miguel', 'Sofia', 
                       'Luis', 'Carmen', 'Antonio', 'Isabel', 'Manuel', 'Teresa', 'Ricardo', 'Patricia'];
        $lastNames = ['Garcia', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Perez', 'Sanchez', 
                      'Ramirez', 'Cruz', 'Flores', 'Ramos', 'Reyes', 'Santos', 'Morales'];
        $positions = ['Administrative Officer', 'Clerk', 'Engineer', 'Nurse', 'Driver', 'Accountant', 'Secretary'];

        for ($i = 2; $i <= 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $middleName = $lastNames[array_rand($lastNames)];
            $empId = 'EMP-' . (rand(0, 1) ? '2023' : '2024') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $email = strtolower($firstName . '.' . $lastName . $i . '@sablayan.gov.ph');
            $fullName = $firstName . ' ' . $lastName;
            
            // Create user account for this employee
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(rand(1, 2)),
            ]);

            // Create employee record linked to user
            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_number' => $empId,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'birth_date' => Carbon::parse(rand(1970, 1995) . '-' . rand(1, 12) . '-' . rand(1, 28)),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'civil_status' => ['single', 'married', 'widowed'][rand(0, 2)],
                'address' => 'Sablayan, Occidental Mindoro',
                'mobile_number' => '0917' . rand(1000000, 9999999),
                'email' => $email,
                'department_id' => $departments->random()->id,
                'position' => $positions[array_rand($positions)],
                'employment_type' => ['permanent', 'job_order', 'contract'][rand(0, 2)],
                'date_hired' => Carbon::now()->subYears(rand(1, 10)),
                'rank_level' => 'normal',
                'status' => 'active',
                'created_at' => Carbon::now()->subYears(rand(1, 2)),
            ]);

            $employees[] = $employee;
        }

        return collect($employees);
    }

    private function createTrainingTopics()
    {
        $topics = [
            ['title' => 'Leadership and Management', 'category' => 'leadership', 'rank_level' => 'higher'],
            ['title' => 'Financial Management', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Microsoft Excel', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Microsoft Word', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Project Management', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Communication Skills', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Disaster Risk Reduction', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Records Management', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Customer Service', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Computer Literacy', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Data Privacy Act', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Anti-Corruption', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Gender and Development', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Social Media Management', 'category' => 'technical', 'rank_level' => 'all'],
            ['title' => 'Public Speaking', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Team Building', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Strategic Planning', 'category' => 'leadership', 'rank_level' => 'higher'],
            ['title' => 'Human Resource Management', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Procurement Procedures', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Accounting Standards', 'category' => 'technical', 'rank_level' => 'normal'],
            ['title' => 'Legal Compliance', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Health and Safety', 'category' => 'compliance', 'rank_level' => 'all'],
            ['title' => 'Conflict Resolution', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Innovation', 'category' => 'leadership', 'rank_level' => 'higher'],
            ['title' => 'Digital Transformation', 'category' => 'technical', 'rank_level' => 'higher'],
            ['title' => 'Performance Management', 'category' => 'leadership', 'rank_level' => 'higher'],
            ['title' => 'Time Management', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Problem Solving', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Emotional Intelligence', 'category' => 'soft_skills', 'rank_level' => 'all'],
            ['title' => 'Change Management', 'category' => 'leadership', 'rank_level' => 'higher'],
        ];

        $trainingTopics = [];
        foreach ($topics as $topic) {
            $trainingTopics[] = TrainingTopic::create([
                'title' => $topic['title'],
                'description' => 'Training on ' . strtolower($topic['title']),
                'category' => $topic['category'],
                'rank_level' => $topic['rank_level'],
                'is_active' => true,
                'created_at' => Carbon::now()->subYears(2),
            ]);
        }

        return collect($trainingTopics);
    }

    private function createTrainings($topics)
    {
        $trainings = [];
        
        // 20 trainings in 2023
        for ($i = 0; $i < 20; $i++) {
            $topic = $topics->random();
            $startDate = Carbon::parse('2023-' . rand(1, 12) . '-' . rand(1, 28));
            
            $training = Training::create([
                'title' => $topic->title . ' Training 2023',
                'description' => 'Comprehensive training on ' . strtolower($topic->title),
                'training_topic_id' => $topic->id,
                'created_by' => 1,
                'type' => rand(0, 1) ? 'internal' : 'external',
                'rank_level' => $topic->rank_level,
                'venue' => 'LGU Sablayan Conference Hall',
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays(rand(1, 3)),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'facilitator' => 'Dr. Juan dela Cruz',
                'duration_hours' => rand(8, 24),
                'status' => 'completed',
                'created_at' => $startDate->copy()->subDays(30),
            ]);
            
            $trainings[] = $training;
        }

        // 20 trainings in 2024
        for ($i = 0; $i < 20; $i++) {
            $topic = $topics->random();
            $startDate = Carbon::parse('2024-' . rand(1, 12) . '-' . rand(1, 28));
            $now = Carbon::now();
            
            if ($startDate < $now->copy()->subDays(7)) {
                $status = 'completed';
            } elseif ($startDate < $now->copy()->addDays(7)) {
                $status = 'ongoing';
            } else {
                $status = 'scheduled';
            }

            $training = Training::create([
                'title' => $topic->title . ' Training 2024',
                'description' => 'Comprehensive training on ' . strtolower($topic->title),
                'training_topic_id' => $topic->id,
                'created_by' => 1,
                'type' => rand(0, 1) ? 'internal' : 'external',
                'rank_level' => $topic->rank_level,
                'venue' => 'LGU Sablayan Conference Hall',
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays(rand(1, 3)),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'facilitator' => 'Prof. Maria Santos',
                'duration_hours' => rand(8, 24),
                'status' => $status,
                'created_at' => $startDate->copy()->subDays(30),
            ]);
            
            $trainings[] = $training;
        }

        return collect($trainings);
    }

    private function createTrainingAttendance($trainings, $employees)
    {
        foreach ($trainings as $training) {
            $attendeeCount = rand(15, 35);
            $attendees = $employees->random(min($attendeeCount, $employees->count()));

            foreach ($attendees as $employee) {
                if ($training->status === 'completed') {
                    $statuses = ['attended', 'attended', 'attended', 'absent'];
                    $status = $statuses[array_rand($statuses)];
                } else {
                    $status = 'registered';
                }

                TrainingAttendance::create([
                    'training_id' => $training->id,
                    'employee_id' => $employee->id,
                    'attendance_status' => $status,
                    'remarks' => $status === 'absent' ? 'No show' : null,
                    'created_at' => $training->start_date->copy()->subDays(rand(5, 15)),
                ]);
            }
        }
    }

    private function createTrainingSurveys($employees, $topics)
    {
        $otherTopicsSuggestions = [
            'Advanced Excel training for data analysis',
            'Leadership and team management',
            'Effective communication skills',
            'Time management and productivity',
            'Customer service excellence',
            null, // Some employees don't suggest additional topics
            null,
        ];

        // 2023 surveys
        $respondents2023 = $employees->random((int)($employees->count() * 0.70));
        foreach ($respondents2023 as $employee) {
            $selectedTopics = $topics->random(rand(3, 7))->pluck('id')->toArray();
            $submittedDate = Carbon::parse('2023-' . rand(1, 3) . '-' . rand(1, 28));

            TrainingSurvey::create([
                'employee_id' => $employee->id,
                'year' => 2023,
                'selected_topics' => json_encode($selectedTopics),
                'other_topics' => $otherTopicsSuggestions[array_rand($otherTopicsSuggestions)],
                'status' => 'submitted',
                'submitted_at' => $submittedDate,
                'created_at' => $submittedDate,
            ]);
        }

        // 2024 surveys
        $respondents2024 = $employees->random((int)($employees->count() * 0.75));
        foreach ($respondents2024 as $employee) {
            $selectedTopics = $topics->random(rand(3, 7))->pluck('id')->toArray();
            $submittedDate = Carbon::parse('2024-' . rand(1, 12) . '-' . rand(1, 28));

            TrainingSurvey::create([
                'employee_id' => $employee->id,
                'year' => 2024,
                'selected_topics' => json_encode($selectedTopics),
                'other_topics' => $otherTopicsSuggestions[array_rand($otherTopicsSuggestions)],
                'status' => 'submitted',
                'submitted_at' => $submittedDate,
                'created_at' => $submittedDate,
            ]);
        }
    }

    private function createEmployeeFiles($employees)
    {
        $fileTypes = ['Personal Data Sheet', 'Birth Certificate', 'Diploma', 'NBI Clearance'];

        foreach ($employees->take(30) as $employee) {
            foreach ($fileTypes as $fileType) {
                EmployeeFile::create([
                    'employee_id' => $employee->id,
                    'file_type' => $fileType,
                    'file_name' => strtolower(str_replace(' ', '_', $fileType)) . '.pdf',
                    'file_path' => 'employee-files/' . $employee->id . '/' . strtolower(str_replace(' ', '_', $fileType)) . '.pdf',
                    'file_size' => rand(100000, 2000000),
                    'uploaded_by' => 1,
                    'created_at' => Carbon::now()->subMonths(rand(1, 24)),
                ]);
            }
        }
    }

    private function createHRDocuments()
    {
        $documents = [
            ['Leave Policy 2023', 'Annual leave guidelines'],
            ['Employee Handbook', 'Complete guide for employees'],
            ['Performance Evaluation Form', 'Assessment template'],
        ];

        foreach ($documents as $doc) {
            HRDocument::create([
                'title' => $doc[0],
                'description' => $doc[1],
                'category' => 'Policy',
                'file_name' => strtolower(str_replace(' ', '_', $doc[0])) . '.pdf',
                'file_path' => 'hr-documents/' . strtolower(str_replace(' ', '_', $doc[0])) . '.pdf',
                'file_size' => rand(500000, 5000000),
                'uploaded_by' => 1,
                'created_at' => Carbon::now()->subMonths(rand(1, 12)),
            ]);
        }
    }

    private function createNotifications($employees, $trainings)
    {
        // Limit to avoid too many records
        $recentTrainings = $trainings->take(10);
        
        foreach ($recentTrainings as $training) {
            $notifyEmployees = $employees->random(min(10, $employees->count()));

            foreach ($notifyEmployees as $employee) {
                if ($employee->user_id) {
                    Notification::create([
                        'user_id' => $employee->user_id,
                        'type' => 'training',
                        'title' => 'New Training Available',
                        'message' => $training->title . ' is now open for enrollment.',
                        'related_id' => $training->id,
                        'related_type' => 'App\Models\Training',
                        'is_read' => rand(1, 100) <= 50,
                        'created_at' => $training->created_at,
                    ]);
                }
            }
        }
    }
}
