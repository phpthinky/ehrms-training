<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EHRMS - LGU Sablayan')</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts - Professional Government Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-green: #059669;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --header-height: 70px;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 20px rgba(30, 64, 175, 0.15);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        /* Logo Area */
        .sidebar-logo {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            min-height: var(--header-height);
        }

        .sidebar-logo-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-blue);
            flex-shrink: 0;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-logo-text {
            color: white;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-logo-text {
            opacity: 0;
            width: 0;
        }

        .sidebar-logo-text h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
        }

        .sidebar-logo-text small {
            font-size: 0.7rem;
            opacity: 0.85;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Navigation Menu */
        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section-title {
            padding: 0.75rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            overflow: hidden;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            position: relative;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: rgba(255, 255, 255, 0.4);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: #fbbf24;
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link-text {
            white-space: nowrap;
            transition: all 0.3s;
        }

        .sidebar.collapsed .nav-link-text {
            opacity: 0;
            width: 0;
        }

        .nav-link .badge {
            margin-left: auto;
            transition: all 0.3s;
        }

        .sidebar.collapsed .nav-link .badge {
            opacity: 0;
            width: 0;
        }

        /* Main Content Area */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .main-wrapper.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        /* Header */
        .main-header {
            background: var(--bg-white);
            height: var(--header-height);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .sidebar-toggle {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 1.5rem;
        }

        .sidebar-toggle:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--text-dark);
            margin: 0;
        }

        .header-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            color: var(--text-muted);
        }

        .header-icon-btn:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .header-icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            border: 2px solid white;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
        }

        .user-profile:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .user-profile:hover .user-info small {
            color: rgba(255, 255, 255, 0.85);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            text-align: left;
        }

        .user-info strong {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-info small {
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px; /* Full width sidebar on mobile */
                transform: translateX(-100%);
                z-index: 1050; /* Above other content */
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
            }

            .main-wrapper {
                margin-left: 0 !important; /* No margin on mobile */
            }

            .main-wrapper.sidebar-open {
                margin-left: 0 !important; /* Ensure no push when sidebar opens */
            }

            .main-header {
                padding: 0 1rem;
                left: 0 !important; /* Full width header */
                width: 100% !important;
            }

            .main-content {
                padding: 1rem;
            }

            /* Sidebar toggle button - always visible on mobile */
            .sidebar-toggle {
                display: flex !important;
            }

            /* Overlay when sidebar is open */
            .sidebar.show::before {
                content: '';
                position: fixed;
                top: 0;
                left: 280px;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: slideIn 0.4s ease-out;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="sidebar-logo-text">
                <h5 class="mb-0">eHRMS</h5>
                <small>LGU Sablayan</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            @auth
                <!-- Dashboard -->
                <div class="nav-section-title">Main</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>

                @if(auth()->user()->isStaff())
                    <!-- Employee Management -->
                    <div class="nav-section-title">Employee Management</div>
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="nav-link-text">Employees</span>
                    </a>
                    <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3"></i>
                        <span class="nav-link-text">Departments</span>
                    </a>

                    <!-- Training Management -->
                    <div class="nav-section-title">Training & Development</div>
                    <a href="{{ route('trainings.index') }}" class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark"></i>
                        <span class="nav-link-text">Trainings</span>
                    </a>
                    <a href="{{ route('training-topics.index') }}" class="nav-link {{ request()->routeIs('training-topics.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span class="nav-link-text">Training Topics</span>
                    </a>
                    <a href="{{ route('surveys.index') }}" class="nav-link {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data"></i>
                        <span class="nav-link-text">Survey Results</span>
                    </a>

                    <!-- Survey System -->
                    <div class="nav-section-title">Survey Management</div>
                    <a href="{{ route('survey-templates.index') }}" class="nav-link {{ request()->routeIs('survey-templates.*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i>
                        <span class="nav-link-text">Survey Templates</span>
                    </a>
                    <a href="{{ route('survey-questions.index') }}" class="nav-link {{ request()->routeIs('survey-questions.*') ? 'active' : '' }}">
                        <i class="bi bi-question-circle"></i>
                        <span class="nav-link-text">Question Bank</span>
                    </a>
                @else
                    <!-- Employee View -->
                    <div class="nav-section-title">My Records</div>
                    <a href="{{ route('my-profile') }}" class="nav-link {{ request()->routeIs('my-profile') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span class="nav-link-text">My Profile</span>
                    </a>
                    <a href="{{ route('my-trainings') }}" class="nav-link {{ request()->routeIs('my-trainings') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark"></i>
                        <span class="nav-link-text">My Trainings</span>
                    </a>
                    <a href="{{ route('my-files') }}" class="nav-link {{ request()->routeIs('my-files') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="nav-link-text">My 201 Files</span>
                    </a>

                    <!-- Survey -->
                    <div class="nav-section-title">Surveys</div>
                    <a href="{{ route('training-survey.form') }}" class="nav-link {{ request()->routeIs('training-survey.form') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span class="nav-link-text">Training Survey</span>
                    </a>
                    <a href="{{ route('survey-responses.form') }}" class="nav-link {{ request()->routeIs('survey-responses.*') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist"></i>
                        <span class="nav-link-text">Annual Survey</span>
                    </a>
                @endif

                <!-- Communication -->
                <div class="nav-section-title">Communication</div>
                <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span class="nav-link-text">Messages</span>
                    @if(isset($unreadMessages) && $unreadMessages > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadMessages }}</span>
                    @endif
                </a>
                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span class="nav-link-text">Notifications</span>
                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadNotifications }}</span>
                    @endif
                </a>

                @if(auth()->user()->isStaff())
                    <!-- Files & Reports -->
                    <div class="nav-section-title">Resources</div>
                    <a href="{{ route('hr-documents.index') }}" class="nav-link {{ request()->routeIs('hr-documents.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span class="nav-link-text">HR Documents</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-bar-graph"></i>
                        <span class="nav-link-text">Reports</span>
                    </a>
                @endif
            @endauth
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        @auth
            <!-- Header -->
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>

                <div class="header-actions">
                    <div class="header-icon-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="badge rounded-pill">{{ $unreadNotifications }}</span>
                        @endif
                    </div>

                    <div class="header-icon-btn" title="Messages">
                        <i class="bi bi-envelope"></i>
                        @if(isset($unreadMessages) && $unreadMessages > 0)
                            <span class="badge rounded-pill">{{ $unreadMessages }}</span>
                        @endif
                    </div>

                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="user-info d-none d-md-block">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                            </div>
                            <i class="bi bi-chevron-down d-none d-md-block"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
        @endauth

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-in" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-in" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const sidebarToggle = document.getElementById('sidebarToggle');

        // Function to handle sidebar behavior based on screen size
        function handleSidebarBehavior() {
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile) {
                // Mobile behavior - overlay sidebar
                sidebar.classList.remove('collapsed');
                mainWrapper.classList.remove('expanded');
                
                // Remove desktop toggle handler
                sidebarToggle.onclick = function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                };
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            } else {
                // Desktop behavior - collapsible sidebar
                sidebar.classList.remove('show');
                
                sidebarToggle.onclick = function() {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                    
                    // Save state to localStorage
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                };
                
                // Restore sidebar state on desktop
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    sidebar.classList.add('collapsed');
                    mainWrapper.classList.add('expanded');
                }
            }
        }

        // Initialize on load
        handleSidebarBehavior();

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                handleSidebarBehavior();
            }, 250);
        });
    </script>

    @stack('scripts')
</body>
</html>
