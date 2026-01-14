<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - EHRMS LGU Sablayan</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-green: #059669;
            --bg-gradient-start: #eff6ff;
            --bg-gradient-end: #dbeafe;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Elements */
        .bg-decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            z-index: 0;
        }

        .decoration-1 {
            width: 600px;
            height: 600px;
            background: var(--primary-blue);
            top: -200px;
            right: -200px;
        }

        .decoration-2 {
            width: 400px;
            height: 400px;
            background: var(--accent-green);
            bottom: -150px;
            left: -150px;
        }

        .decoration-3 {
            width: 300px;
            height: 300px;
            background: var(--primary-dark);
            top: 40%;
            right: 10%;
        }

        /* Content */
        .hero-content {
            position: relative;
            z-index: 1;
        }

        .logo-badge {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(30, 64, 175, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 3.5rem;
            color: var(--primary-dark);
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #475569;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .hero-description {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 14px;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(30, 64, 175, 0.4);
        }

        /* Feature Cards */
        .features-section {
            margin-top: 4rem;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(30, 64, 175, 0.1);
            transition: all 0.3s;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-blue);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(30, 58, 138, 0.1));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
        }

        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
        }

        .feature-description {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            margin-top: 5rem;
            text-align: center;
        }

        .stat-item {
            padding: 1.5rem;
        }

        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            color: var(--primary-blue);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #64748b;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Image Section */
        .hero-image {
            position: relative;
            z-index: 1;
        }

        .image-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            transform: rotate(3deg);
            transition: all 0.5s;
        }

        .image-card:hover {
            transform: rotate(0deg) scale(1.05);
        }

        .image-placeholder {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 16px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 5rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .decoration-1, .decoration-2, .decoration-3 {
                display: none;
            }
        }

        /* Animation */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slideUp 0.8s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
    </style>
</head>
<body>
    <!-- Background Decorations -->
    <div class="bg-decoration decoration-1"></div>
    <div class="bg-decoration decoration-2"></div>
    <div class="bg-decoration decoration-3"></div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="logo-badge animate-slide-up">
                        <i class="bi bi-building"></i>
                    </div>
                    
                    <h1 class="hero-title animate-slide-up delay-1">
                        Electronic Human Resource Management System
                    </h1>
                    
                    <p class="hero-subtitle animate-slide-up delay-2">
                        Local Government Unit of Sablayan
                    </p>
                    
                    <p class="hero-description animate-slide-up delay-3">
                        Streamlined employee records management, training coordination, and document tracking for nearly 1,000 LGU employees. Built for efficiency, compliance, and transparency.
                    </p>
                    
                    <div class="animate-slide-up delay-4">
                        <a href="{{ route('login') }}" class="btn btn-primary-custom text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Access System
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="stats-section">
                        <div class="row">
                            <div class="col-4 stat-item animate-slide-up delay-4">
                                <div class="stat-number">1K+</div>
                                <div class="stat-label">Employees</div>
                            </div>
                            <div class="col-4 stat-item animate-slide-up delay-4">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Digital</div>
                            </div>
                            <div class="col-4 stat-item animate-slide-up delay-4">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Access</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 hero-image d-none d-lg-block">
                    <div class="image-card animate-slide-up delay-2">
                        <div class="image-placeholder">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="features-section">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card animate-slide-up delay-1">
                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-person"></i>
                            </div>
                            <h3 class="feature-title">201 Files Management</h3>
                            <p class="feature-description">
                                Secure digital storage for employee records, certificates, and compliance documents with role-based access control.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card animate-slide-up delay-2">
                            <div class="feature-icon">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <h3 class="feature-title">Training Management</h3>
                            <p class="feature-description">
                                Organize internal and external trainings, track attendance, manage approvals, and monitor certificate submissions.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card animate-slide-up delay-3">
                            <div class="feature-icon">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <h3 class="feature-title">Training Needs Analysis</h3>
                            <p class="feature-description">
                                Annual survey system to identify employee training preferences and help HR plan relevant development programs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
