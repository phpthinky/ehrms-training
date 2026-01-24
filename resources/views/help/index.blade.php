@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-2">
            <i class="bi bi-question-circle me-2"></i>Help & User Guide
        </h1>
        <p class="text-muted">Comprehensive guide to using the LGU Sablayan EHRMS</p>
    </div>

    <!-- Quick Links -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="mb-3"><i class="bi bi-bookmark-star me-2"></i>Quick Access</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="#getting-started" class="btn btn-outline-primary w-100">
                        <i class="bi bi-play-circle me-2"></i>Getting Started
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#role-guides" class="btn btn-outline-success w-100">
                        <i class="bi bi-person-badge me-2"></i>Role Guides
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#features" class="btn btn-outline-info w-100">
                        <i class="bi bi-grid me-2"></i>Features
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#faq" class="btn btn-outline-warning w-100">
                        <i class="bi bi-question-circle me-2"></i>FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Getting Started -->
    <div class="card border-0 shadow-sm mb-4" id="getting-started">
        <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0"><i class="bi bi-play-circle me-2"></i>Getting Started</h5>
        </div>
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">First Time Login</h6>
            <ol class="mb-4">
                <li class="mb-2">Open your browser and navigate to the EHRMS URL provided by your HR department</li>
                <li class="mb-2">Enter your email address and password (check your welcome email for credentials)</li>
                <li class="mb-2">Click the <span class="badge bg-primary">Login</span> button</li>
                <li class="mb-2">After first login, it's recommended to change your password via Settings</li>
            </ol>

            <h6 class="fw-bold mb-3">Dashboard Overview</h6>
            <p>After logging in, you'll see your personalized dashboard with:</p>
            <ul class="mb-4">
                <li><strong>Statistics Cards:</strong> Quick overview of key metrics</li>
                <li><strong>Recent Activities:</strong> Latest updates and notifications</li>
                <li><strong>Quick Actions:</strong> Shortcuts to commonly used features</li>
                <li><strong>Training Calendar:</strong> Upcoming training schedules</li>
            </ul>

            <h6 class="fw-bold mb-3">Navigation</h6>
            <p>Use the sidebar menu to access different modules:</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <i class="bi bi-speedometer2 text-primary me-2"></i><strong>Dashboard:</strong> Home page with overview
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <i class="bi bi-people text-success me-2"></i><strong>Employees:</strong> Staff directory and records
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <i class="bi bi-book text-info me-2"></i><strong>Trainings:</strong> Training programs and schedules
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <i class="bi bi-bell text-warning me-2"></i><strong>Notifications:</strong> System alerts and updates
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role-Specific Guides -->
    <div class="card border-0 shadow-sm mb-4" id="role-guides">
        <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Role-Specific Guides</h5>
        </div>
        <div class="card-body p-4">
            <!-- HR Admin Guide -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">
                    <i class="bi bi-shield-check me-2"></i>HR Admin Guide
                </h5>
                <div class="accordion" id="hrAdminAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#hrManageEmployees">
                                Managing Employees
                            </button>
                        </h2>
                        <div id="hrManageEmployees" class="accordion-collapse collapse show" data-bs-parent="#hrAdminAccordion">
                            <div class="accordion-body">
                                <p><strong>Adding a New Employee:</strong></p>
                                <ol>
                                    <li>Go to <strong>Employees</strong> → <strong>Create New</strong></li>
                                    <li>Fill in personal information (required fields marked with *)</li>
                                    <li>Assign department and position</li>
                                    <li>Set employment status and type</li>
                                    <li>Click <span class="badge bg-success">Save Employee</span></li>
                                    <li>Employee will receive welcome email with login credentials</li>
                                </ol>
                                <p><strong>Editing Employee Records:</strong></p>
                                <ol>
                                    <li>Find employee in the list or use search</li>
                                    <li>Click <span class="badge bg-primary">Edit</span> button</li>
                                    <li>Update necessary information</li>
                                    <li>Click <span class="badge bg-success">Update Employee</span></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hrManageTraining">
                                Managing Trainings
                            </button>
                        </h2>
                        <div id="hrManageTraining" class="accordion-collapse collapse" data-bs-parent="#hrAdminAccordion">
                            <div class="accordion-body">
                                <p><strong>Creating a Training Program:</strong></p>
                                <ol>
                                    <li>Navigate to <strong>Trainings</strong> → <strong>Create New</strong></li>
                                    <li>Enter training title and description</li>
                                    <li>Select training topic/category</li>
                                    <li>Set start and end dates</li>
                                    <li>Choose venue and facilitator</li>
                                    <li>Set maximum participants (optional)</li>
                                    <li>Click <span class="badge bg-success">Create Training</span></li>
                                </ol>
                                <p><strong>Managing Attendance:</strong></p>
                                <ol>
                                    <li>Go to <strong>Training Attendance</strong></li>
                                    <li>Select the training session</li>
                                    <li>Mark employees as Present, Absent, or Excused</li>
                                    <li>Add remarks if needed</li>
                                    <li>Click <span class="badge bg-success">Save Attendance</span></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hrSurveys">
                                Survey Management
                            </button>
                        </h2>
                        <div id="hrSurveys" class="accordion-collapse collapse" data-bs-parent="#hrAdminAccordion">
                            <div class="accordion-body">
                                <p><strong>Creating Survey Templates:</strong></p>
                                <ol>
                                    <li>Go to <strong>Survey Templates</strong> → <strong>Create New</strong></li>
                                    <li>Enter template name and description</li>
                                    <li>Add questions from Question Bank or create new ones</li>
                                    <li>Arrange questions using drag-and-drop</li>
                                    <li>Set required fields</li>
                                    <li>Activate template when ready</li>
                                </ol>
                                <p><strong>Viewing Survey Analytics:</strong></p>
                                <ol>
                                    <li>Navigate to <strong>Survey Templates</strong></li>
                                    <li>Click <span class="badge bg-info">Analytics</span> on desired survey</li>
                                    <li>Use filters (date range, department) to refine results</li>
                                    <li>View charts and statistical summaries</li>
                                    <li>Export data if needed</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hrReports">
                                Reports & Analytics
                            </button>
                        </h2>
                        <div id="hrReports" class="accordion-collapse collapse" data-bs-parent="#hrAdminAccordion">
                            <div class="accordion-body">
                                <p><strong>Available Reports:</strong></p>
                                <ul>
                                    <li><strong>Training Participation Trend:</strong> Monthly training statistics</li>
                                    <li><strong>Training by Type:</strong> Distribution of training categories</li>
                                    <li><strong>Department Training:</strong> Training participation per department</li>
                                    <li><strong>Survey Response Rate:</strong> Employee survey participation</li>
                                </ul>
                                <p><strong>Training Needs Analysis (TNA):</strong></p>
                                <ol>
                                    <li>Access <strong>TNA Recommendations</strong> from dashboard or training menu</li>
                                    <li>Review requested training topics with priority levels</li>
                                    <li>Filter by year to view historical data</li>
                                    <li>Plan future trainings based on critical/high priority items</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Guide -->
            <div class="mb-4">
                <h5 class="text-success mb-3">
                    <i class="bi bi-person me-2"></i>Employee Guide
                </h5>
                <div class="accordion" id="employeeAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#empProfile">
                                Viewing Your Profile
                            </button>
                        </h2>
                        <div id="empProfile" class="accordion-collapse collapse show" data-bs-parent="#employeeAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Click on your name in the top-right corner</li>
                                    <li>Select <strong>Profile</strong> from dropdown</li>
                                    <li>View your personal information and employment details</li>
                                    <li>Update your contact information if allowed</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#empTraining">
                                Accessing Trainings
                            </button>
                        </h2>
                        <div id="empTraining" class="accordion-collapse collapse" data-bs-parent="#employeeAccordion">
                            <div class="accordion-body">
                                <p><strong>Viewing Available Trainings:</strong></p>
                                <ol>
                                    <li>Go to <strong>Trainings</strong> from sidebar</li>
                                    <li>Browse scheduled training programs</li>
                                    <li>Click on a training to view details</li>
                                </ol>
                                <p><strong>Your Training History:</strong></p>
                                <ol>
                                    <li>Navigate to <strong>Training Attendance</strong></li>
                                    <li>View trainings you've attended</li>
                                    <li>Check attendance status and dates</li>
                                    <li>Download certificates if available</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#empSurvey">
                                Completing Surveys
                            </button>
                        </h2>
                        <div id="empSurvey" class="accordion-collapse collapse" data-bs-parent="#employeeAccordion">
                            <div class="accordion-body">
                                <p><strong>Taking the Annual Training Survey:</strong></p>
                                <ol>
                                    <li>When notified, go to <strong>Annual Survey</strong></li>
                                    <li>Read instructions carefully</li>
                                    <li>Answer all required questions (marked with *)</li>
                                    <li>Review your responses</li>
                                    <li>Click <span class="badge bg-success">Submit Survey</span></li>
                                    <li>You'll receive confirmation upon submission</li>
                                </ol>
                                <p class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tip: Your survey responses help HR plan relevant training programs for you!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#empNotif">
                                Managing Notifications
                            </button>
                        </h2>
                        <div id="empNotif" class="accordion-collapse collapse" data-bs-parent="#employeeAccordion">
                            <div class="accordion-body">
                                <p><strong>Viewing Notifications:</strong></p>
                                <ol>
                                    <li>Click the bell icon <i class="bi bi-bell"></i> in the header</li>
                                    <li>View recent notifications in dropdown</li>
                                    <li>Click on a notification to view details</li>
                                    <li>Mark as read to clear from unread list</li>
                                </ol>
                                <p><strong>Types of Notifications You'll Receive:</strong></p>
                                <ul>
                                    <li>Training registration confirmations</li>
                                    <li>Training schedule updates</li>
                                    <li>Attendance status changes</li>
                                    <li>Survey reminders</li>
                                    <li>System announcements</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Assistant Guide -->
            <div>
                <h5 class="text-info mb-3">
                    <i class="bi bi-person-gear me-2"></i>Admin Assistant Guide
                </h5>
                <p class="text-muted">
                    Admin Assistants have similar permissions to HR Admins but with limited administrative functions.
                    Refer to the HR Admin guide above for most tasks. You can manage employees, trainings, and view reports,
                    but certain system settings may be restricted.
                </p>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="card border-0 shadow-sm mb-4" id="features">
        <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h5 class="mb-0"><i class="bi bi-grid me-2"></i>System Features</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Employee Management</h6>
                            <p class="text-muted small mb-0">
                                Complete 201 files, employee records, department assignment,
                                and personnel information management.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="bi bi-book text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Training Management</h6>
                            <p class="text-muted small mb-0">
                                Schedule trainings, manage attendance, track participation,
                                and generate training reports.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="bi bi-clipboard-data text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Survey System</h6>
                            <p class="text-muted small mb-0">
                                Customizable survey templates, question bank, dynamic forms,
                                and comprehensive analytics.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="bi bi-graph-up text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Reports & Analytics</h6>
                            <p class="text-muted small mb-0">
                                Visual charts, training trends, department statistics,
                                and Training Needs Analysis (TNA) recommendations.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded p-3">
                                <i class="bi bi-bell text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Notifications</h6>
                            <p class="text-muted small mb-0">
                                Real-time system alerts for training updates, document uploads,
                                and important announcements.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary bg-opacity-10 rounded p-3">
                                <i class="bi bi-file-earmark-text text-secondary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">HR Documents</h6>
                            <p class="text-muted small mb-0">
                                Centralized repository for policies, forms, memos,
                                and other HR-related documents.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="card border-0 shadow-sm mb-4" id="faq">
        <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Frequently Asked Questions</h5>
        </div>
        <div class="card-body p-4">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I reset my password?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>To change your password:</p>
                            <ol>
                                <li>Click on your name in the top-right corner</li>
                                <li>Select <strong>Settings</strong></li>
                                <li>Go to the "Change Password" section</li>
                                <li>Enter your current password and new password</li>
                                <li>Click <span class="badge bg-primary">Update Password</span></li>
                            </ol>
                            <p class="text-muted small mb-0">
                                If you forgot your password, contact your HR Admin for assistance.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Why can't I see certain menu items?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Menu items are role-based. You only see features you have permission to access:
                            <ul class="mb-0">
                                <li><strong>HR Admin:</strong> Full access to all features</li>
                                <li><strong>Admin Assistant:</strong> Limited administrative access</li>
                                <li><strong>Employee:</strong> Personal records and trainings only</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            How do I know if I have new notifications?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The bell icon <i class="bi bi-bell"></i> in the header will show a red badge with the number
                            of unread notifications. Click the bell to view them in a dropdown, or go to the Notifications
                            page to see all notifications.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Can I edit a survey after submitting it?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            No, once a survey is submitted, it cannot be edited. Please review your answers carefully
                            before clicking the Submit button. If you need to make changes after submission,
                            contact your HR Admin for assistance.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            How are training recommendations determined?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Training recommendations are based on the Training Needs Analysis (TNA) from annual surveys.
                            The system analyzes all employee responses to identify the most requested training topics.
                            Topics are prioritized as:
                            <ul class="mb-0">
                                <li><strong class="text-danger">Critical:</strong> ≥70% of employees requested</li>
                                <li><strong class="text-warning">High:</strong> ≥50% of employees requested</li>
                                <li><strong class="text-info">Medium:</strong> ≥30% of employees requested</li>
                                <li><strong class="text-secondary">Low:</strong> <30% of employees requested</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                            What browsers are supported?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            EHRMS works best on modern browsers:
                            <ul class="mb-0">
                                <li>Google Chrome (recommended) - latest version</li>
                                <li>Mozilla Firefox - latest version</li>
                                <li>Microsoft Edge - latest version</li>
                                <li>Safari - latest version</li>
                            </ul>
                            <p class="text-muted small mb-0 mt-2">
                                For best performance, keep your browser updated to the latest version.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                            Who do I contact for technical support?
                        </button>
                    </h2>
                    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>For technical issues or questions:</p>
                            <ul class="mb-2">
                                <li><strong>Email:</strong> it@sablayan.gov.ph</li>
                                <li><strong>Phone:</strong> (043) 123-4567</li>
                                <li><strong>Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</li>
                            </ul>
                            <p class="text-muted small mb-0">
                                For HR-related questions, contact your HR Admin or Admin Assistant.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>System Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>System Name:</strong> LGU Sablayan EHRMS</p>
                    <p><strong>Version:</strong> 2.0</p>
                    <p><strong>Release Date:</strong> January 2026</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Developer:</strong> Harold</p>
                    <p><strong>Client:</strong> LGU Sablayan, Occidental Mindoro</p>
                    <p><strong>Framework:</strong> Laravel 11.x</p>
                </div>
            </div>
            <hr>
            <p class="text-muted small mb-0">
                <i class="bi bi-shield-check me-1"></i>
                This system is proprietary to the Municipality of Sablayan.
                All data is confidential and protected under local government regulations.
            </p>
        </div>
    </div>

    <!-- Back to Top -->
    <div class="text-center mb-4">
        <a href="#top" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-up me-2"></i>Back to Top
        </a>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #212529;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0,0,0,.125);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
