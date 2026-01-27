<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Models\TrainingSurvey;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        $stats = [
            'totalEmployees' => Employee::count(),
            'totalTrainings' => Training::count(),
            'totalAttendance' => TrainingAttendance::count(),
            'totalSurveys' => TrainingSurvey::where('status', 'submitted')->count(),
        ];

        // Chart Data
        $chartData = $this->getChartData();

        return view('reports.index', compact('stats', 'chartData'));
    }

    /**
     * Get chart data for reports dashboard
     */
    private function getChartData()
    {
        $currentYear = date('Y');

        // Training Trend - Monthly data
        $trainingTrend = DB::table('trainings')
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->whereYear('start_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $trainingTrendData = [];
        for ($i = 1; $i <= 12; $i++) {
            $trainingTrendData[] = $trainingTrend[$i] ?? 0;
        }

        // Training Types Distribution
        $trainingTypes = Training::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        $totalTrainings = $trainingTypes->sum('count');
        $typeLabels = [];
        $typeData = [];
        foreach ($trainingTypes as $type) {
            $typeLabels[] = ucfirst($type->type);
            $typeData[] = $totalTrainings > 0 ? round(($type->count / $totalTrainings) * 100, 1) : 0;
        }

        // Department Training Distribution (Top 5)
        $prefix = config('database.connections.mysql.prefix', 'hr_');
        $departmentTraining = DB::table('training_attendance')
            ->join('employees', 'training_attendance.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->selectRaw("{$prefix}departments.name, COUNT(DISTINCT {$prefix}training_attendance.employee_id) as count")
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Survey Response Rate by Department (Top 5)
        $surveyResponse = DB::table('training_surveys')
            ->join('employees', 'training_surveys.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->where('training_surveys.status', 'submitted')
            ->selectRaw("{$prefix}departments.name, COUNT({$prefix}training_surveys.id) as responses, COUNT(DISTINCT {$prefix}employees.id) as total_employees")
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('responses')
            ->limit(5)
            ->get();

        $surveyLabels = [];
        $surveyData = [];
        foreach ($surveyResponse as $dept) {
            $surveyLabels[] = $dept->name;
            $employeesInDept = Employee::where('department_id', Department::where('name', $dept->name)->value('id'))->count();
            $surveyData[] = $employeesInDept > 0 ? round(($dept->responses / $employeesInDept) * 100, 1) : 0;
        }

        return [
            'trainingTrend' => [
                'labels' => $months,
                'data' => $trainingTrendData
            ],
            'trainingTypes' => [
                'labels' => $typeLabels,
                'data' => $typeData
            ],
            'departmentTraining' => [
                'labels' => $departmentTraining->pluck('name')->toArray(),
                'data' => $departmentTraining->pluck('count')->toArray()
            ],
            'surveyResponse' => [
                'labels' => $surveyLabels,
                'data' => $surveyData
            ]
        ];
    }

    /**
     * Training participation report
     */
    public function trainingReport(Request $request)
    {
        $query = Training::with(['topic', 'attendances.employee.department']);

        // Date filters
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Topic filter
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $trainings = $query->orderBy('start_date', 'desc')->get();

        // Calculate statistics
        $reportData = [
            'trainings' => $trainings,
            'totalTrainings' => $trainings->count(),
            'totalParticipants' => $trainings->sum(function($t) { 
                return $t->attendances->count(); 
            }),
            'totalAttended' => $trainings->sum(function($t) { 
                return $t->attendances->where('status', 'attended')->count(); 
            }),
            'totalAbsent' => $trainings->sum(function($t) { 
                return $t->attendances->where('status', 'absent')->count(); 
            }),
            'attendanceRate' => 0,
        ];

        $totalParticipants = $reportData['totalParticipants'];
        if ($totalParticipants > 0) {
            $reportData['attendanceRate'] = round(($reportData['totalAttended'] / $totalParticipants) * 100, 1);
        }

        return view('reports.training-report', $reportData);
    }

    /**
     * Employee training history report
     */
    public function employeeReport(Request $request)
    {
        $query = Employee::with(['department', 'trainings']);

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Rank filter
        if ($request->filled('rank_level')) {
            $query->where('rank_level', $request->rank_level);
        }

        $employees = $query->orderBy('last_name')->get();

        // Calculate training statistics per employee
        $employeeData = $employees->map(function($employee) {
            $attendances = TrainingAttendance::where('employee_id', $employee->id)->get();
            
            return [
                'employee' => $employee,
                'total_trainings' => $attendances->count(),
                'attended' => $attendances->where('attendance_status', 'attended')->count(),
                'absent' => $attendances->where('attendance_status', 'absent')->count(),
                'registered' => $attendances->where('attendance_status', 'registered')->count(),
                'certificates' => $attendances->whereNotNull('certificate_path')->count(),
            ];
        });

        $reportData = [
            'employeeData' => $employeeData,
            'totalEmployees' => $employees->count(),
            'totalTrainings' => $employeeData->sum('total_trainings'),
            'averageTrainings' => $employees->count() > 0 ? 
                round($employeeData->sum('total_trainings') / $employees->count(), 1) : 0,
        ];

        return view('reports.employee-report', $reportData);
    }

    /**
     * Survey analysis report
     */
    public function surveyReport(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Get all surveys for the year
        $surveys = TrainingSurvey::with(['employee.department', 'topics'])
                                ->where('year', $year)
                                ->where('status', 'submitted')
                                ->get();

        // Calculate statistics
        $totalEmployees = Employee::where('status', 'active')->count();
        $submittedCount = $surveys->count();
        $responseRate = $totalEmployees > 0 ? round(($submittedCount / $totalEmployees) * 100, 1) : 0;

        // Topic popularity
        $topicCounts = [];
        foreach ($surveys as $survey) {
            foreach ($survey->topics as $topic) {
                if (!isset($topicCounts[$topic->id])) {
                    $topicCounts[$topic->id] = [
                        'topic' => $topic,
                        'count' => 0,
                    ];
                }
                $topicCounts[$topic->id]['count']++;
            }
        }

        // Sort by count
        usort($topicCounts, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Schedule preferences
        $schedulePreferences = $surveys->groupBy('preferred_schedule')
                                      ->map(function($group) {
                                          return $group->count();
                                      });

        // Format preferences
        $formatPreferences = $surveys->groupBy('preferred_format')
                                    ->map(function($group) {
                                        return $group->count();
                                    });

        // Department breakdown
        $departmentBreakdown = $surveys->groupBy('employee.department.name')
                                      ->map(function($group) {
                                          return $group->count();
                                      });

        $reportData = [
            'year' => $year,
            'totalEmployees' => $totalEmployees,
            'submittedCount' => $submittedCount,
            'pendingCount' => $totalEmployees - $submittedCount,
            'responseRate' => $responseRate,
            'topTopics' => array_slice($topicCounts, 0, 10),
            'schedulePreferences' => $schedulePreferences,
            'formatPreferences' => $formatPreferences,
            'departmentBreakdown' => $departmentBreakdown,
            'surveys' => $surveys,
        ];

        return view('reports.survey-report', $reportData);
    }

    /**
     * Department statistics report
     */
    public function departmentReport(Request $request)
    {
        $departments = Department::with('employees')
                                 ->withCount('employees')
                                 ->where(function($query) {
                                     $query->where('is_active', true)
                                           ->orWhereNull('is_active');
                                 })
                                 ->get();

        $departmentData = $departments->map(function($dept) {
            // Get training statistics for this department
            $employeeIds = $dept->employees->pluck('id');
            
            $trainings = TrainingAttendance::whereIn('employee_id', $employeeIds)->get();
            
            return [
                'department' => $dept,
                'employee_count' => $dept->employees_count,
                'total_trainings' => $trainings->count(),
                'attended' => $trainings->where('status', 'attended')->count(),
                'average_per_employee' => $dept->employees_count > 0 ? 
                    round($trainings->count() / $dept->employees_count, 1) : 0,
            ];
        })->sortByDesc('employee_count');

        $reportData = [
            'departmentData' => $departmentData,
            'totalDepartments' => $departments->count(),
            'totalEmployees' => $departmentData->sum('employee_count'),
            'totalTrainings' => $departmentData->sum('total_trainings'),
        ];

        return view('reports.department-report', $reportData);
    }

    /**
     * Export training report to CSV
     */
    public function exportTrainingCSV(Request $request)
    {
        $query = Training::with(['topic', 'attendances.employee']);

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $trainings = $query->orderBy('start_date', 'desc')->get();

        $filename = 'training_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($trainings) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Training Title',
                'Topic',
                'Type',
                'Start Date',
                'End Date',
                'Venue',
                'Total Participants',
                'Attended',
                'Absent',
                'Registered',
                'Attendance Rate'
            ]);

            // Data rows
            foreach ($trainings as $training) {
                $total = $training->attendances->count();
                $attended = $training->attendances->where('attendance_status', 'attended')->count();
                $absent = $training->attendances->where('attendance_status', 'absent')->count();
                $registered = $training->attendances->where('attendance_status', 'registered')->count();
                $rate = $total > 0 ? round(($attended / $total) * 100, 1) . '%' : '0%';

                fputcsv($file, [
                    $training->title,
                    $training->topic->title ?? 'N/A',
                    ucfirst($training->type),
                    $training->start_date,
                    $training->end_date,
                    $training->venue ?? 'N/A',
                    $total,
                    $attended,
                    $absent,
                    $registered,
                    $rate
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export employee report to CSV
     */
    public function exportEmployeeCSV(Request $request)
    {
        $employees = Employee::with(['department'])->orderBy('last_name')->get();

        $filename = 'employee_training_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Employee Number',
                'Last Name',
                'First Name',
                'Department',
                'Position',
                'Total Trainings',
                'Attended',
                'Absent',
                'Registered',
                'Certificates'
            ]);

            // Data rows
            foreach ($employees as $employee) {
                $attendances = TrainingAttendance::where('employee_id', $employee->id)->get();
                
                fputcsv($file, [
                    $employee->employee_number,
                    $employee->last_name,
                    $employee->first_name,
                    $employee->department->name ?? 'N/A',
                    $employee->position ?? 'N/A',
                    $attendances->count(),
                    $attendances->where('attendance_status', 'attended')->count(),
                    $attendances->where('attendance_status', 'absent')->count(),
                    $attendances->where('attendance_status', 'registered')->count(),
                    $attendances->whereNotNull('certificate_path')->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
