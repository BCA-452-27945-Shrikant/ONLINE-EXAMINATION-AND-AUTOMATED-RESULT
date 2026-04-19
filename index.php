<?php
// We include config to show live stats on the landing page
require_once 'config/config.php';

// Fetch some live stats to impress the examiners
$total_exams = $conn->query("SELECT sub_id FROM subjects")->num_rows;
$total_students = $conn->query("SELECT user_id FROM users")->num_rows;
$total_results = $conn->query("SELECT res_id FROM results")->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamFlow | Modern Online Examination System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=rel" rel="stylesheet">
    <style>
        :root { 
            --primary-color: #0d6efd; 
            --dark-bg: #212529;
            --gradient-primary: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }
        
        body { 
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        /* Navbar Styles */
        .navbar {
            background: rgba(33, 37, 41, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            color: var(--primary-color);
            transform: rotate(-10deg);
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover i {
            transform: rotate(0deg);
        }
        
        .nav-link {
            font-weight: 500;
            position: relative;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 30px;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(33, 37, 41, 0.95)), 
                        url('https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 140px 0 180px;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
            margin-bottom: 80px;
        }
        
        .hero-section h1 {
            animation: fadeInUp 1s ease;
            text-shadow: 2px 2px 20px rgba(0,0,0,0.3);
        }
        
        .hero-section p {
            animation: fadeInUp 1s ease 0.2s both;
        }
        
        .hero-section .d-flex {
            animation: fadeInUp 1s ease 0.4s both;
        }
        
        /* Floating Stats Cards */
        .stats-container {
            position: relative;
            z-index: 10;
            margin-top: -100px;
        }
        
        .stat-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 2rem 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .stat-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(13, 110, 253, 0.2);
        }
        
        .stat-card h2 {
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            font-size: 1.1rem;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Feature Cards */
        .feature-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 2.5rem 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            opacity: 0.05;
            border-radius: 50%;
            transform: translate(30px, 30px);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(13, 110, 253, 0.15);
        }
        
        .feature-card:hover::after {
            transform: scale(1.5) translate(20px, 20px);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            background: var(--primary-color);
            color: white;
            transform: rotateY(180deg);
        }
        
        .feature-card h5 {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
        }
        
        /* About Section */
        .about-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .about-image {
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .about-image:hover {
            transform: scale(1.02);
            box-shadow: 0 40px 80px rgba(13, 110, 253, 0.3);
        }
        
        .about-badge {
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list i {
            color: var(--primary-color);
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .footer h4 {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        /* Buttons */
        .btn {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(13, 110, 253, 0.4);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .btn-outline-light:hover {
            background: white;
            color: var(--dark-bg) !important;
            transform: translateY(-3px);
        }
        
        .btn-light {
            background: white;
            color: var(--dark-bg);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .btn-light:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 0 150px;
            }
            
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .stat-card h2 {
                font-size: 2.5rem;
            }
            
            .stats-container {
                margin-top: -50px;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                EXAMFLOW
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Project</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="login.php" class="btn btn-outline-light rounded-pill px-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Student Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="admin/index.php" class="btn btn-primary rounded-pill px-4 shadow">
                            <i class="fas fa-lock me-2"></i>Admin Portal
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3">
                Simplify Your <span style="color: #ffc107;">Examinations</span>
            </h1>
            <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 800px;">
                A robust platform for automated testing, secure evaluations, and instant result management.
                <br>Tailored for modern educational institutions.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="register.php" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fas fa-user-plus me-2"></i>Register Now
                </a>
                <a href="#features" class="btn btn-light btn-lg px-5 py-3">
                    <i class="fas fa-play me-2"></i>Explore Features
                </a>
            </div>
            
            <!-- Floating Scroll Indicator -->
            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5">
                <div class="text-white-50" style="animation: float 2s infinite;">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <section class="stats-container">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <h2 class="fw-bold"><?php echo $total_students; ?>+</h2>
                        <p>Active Students</p>
                        <small class="text-muted">Enrolled learners</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <h2 class="fw-bold"><?php echo $total_exams; ?>+</h2>
                        <p>Live Subjects</p>
                        <small class="text-muted">Active examinations</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <h2 class="fw-bold"><?php echo $total_results; ?>+</h2>
                        <p>Results Generated</p>
                        <small class="text-muted">And counting</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="about-badge">System Capabilities</span>
                <h2 class="fw-bold display-5 mb-3">Everything You Need</h2>
                <p class="text-muted fs-5">Comprehensive tools for digital examination management</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <h5>Timed Examinations</h5>
                        <p>Automatic submission logic via JavaScript when the exam timer expires. No manual submission needed.</p>
                        <div class="mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary">Auto-submit</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2">Countdown</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5>Secure Logic</h5>
                        <p>One-time attempt restriction and randomized question order for every student. Anti-cheat measures included.</p>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-success">Anti-cheat</span>
                            <span class="badge bg-success bg-opacity-10 text-success ms-2">Randomized</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h5>PDF Scorecards</h5>
                        <p>Instantly generate and download professional performance reports in PDF format with detailed analytics.</p>
                        <div class="mt-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger">Printable</span>
                            <span class="badge bg-danger bg-opacity-10 text-danger ms-2">Downloadable</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80" 
                             class="img-fluid about-image" 
                             alt="Project Showcase">
                        <div class="position-absolute bottom-0 end-0 bg-primary text-white p-3 rounded-3 shadow-lg" 
                             style="transform: translate(20px, -20px);">
                            <i class="fas fa-code fa-2x"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0">
                    <span class="about-badge">
                        <i class="fas fa-graduation-cap me-2"></i>BCA Final Year Project
                    </span>
                    
                    <h2 class="fw-bold display-6 mb-3">Technical Architecture</h2>
                    
                    <p class="text-muted lead fs-6 mb-4">
                        Built using a robust 3-tier architecture: 
                        <span class="fw-bold text-primary">Frontend</span> (Bootstrap 5), 
                        <span class="fw-bold text-success">Logic</span> (PHP 8.x), and 
                        <span class="fw-bold text-warning">Storage</span> (MySQL).
                    </p>
                    
                    <ul class="list-unstyled feature-list">
                        <li class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <span><strong>RDBMS</strong> – Relational Database Management with optimized queries</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <span><strong>Authentication</strong> – Session-based Secure Login System</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <span><strong>Responsive</strong> – Fully Mobile-Ready Design</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <span><strong>Real-time Stats</strong> – Live dashboard with metrics</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-4">
                            <div>
                                <h4 class="fw-bold text-primary mb-0">PHP 8.x</h4>
                                <small class="text-muted">Backend Logic</small>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h4 class="fw-bold text-primary mb-0">MySQL</h4>
                                <small class="text-muted">Database</small>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h4 class="fw-bold text-primary mb-0">Bootstrap 5</h4>
                                <small class="text-muted">Frontend</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5">
        <div class="container">
            <div class="card border-0 bg-gradient-primary text-white p-5 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-3">Ready to Get Started?</h3>
                        <p class="opacity-75 mb-4">Join thousands of students taking online examinations with ExamFlow</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="register.php" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-user-plus me-2"></i>Register Now
                            </a>
                            <a href="login.php" class="btn btn-outline-light btn-lg px-5">
                                <i class="fas fa-sign-in-alt me-2"></i>Student Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-white py-5">
        <div class="container text-center">
            <h4 class="fw-bold mb-4">EXAMFLOW</h4>
            <p class="text-secondary small mb-4" style="max-width: 600px; margin: 0 auto;">
                This project is developed as a part of the BCA 3rd Year Curriculum.<br>
                Title: Online Examination and Automated Result Management System.
            </p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a>
                <span class="text-white-50">•</span>
                <a href="#" class="text-white-50 text-decoration-none">Terms of Service</a>
                <span class="text-white-50">•</span>
                <a href="#" class="text-white-50 text-decoration-none">Contact Us</a>
            </div>
            
            <div class="mt-4 border-top border-secondary pt-4">
                <p class="small text-secondary mb-0">
                    <i class="fas fa-copyright me-1"></i> 2026 ExamFlow System. All Rights Reserved.
                    <br>
                    <span class="opacity-50">Made with <i class="fas fa-heart text-danger"></i> for Education</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg"
            style="width: 50px; height: 50px; display: none; z-index: 9999;"
            id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show/Hide Back to Top Button
        window.addEventListener('scroll', function() {
            var backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        
        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar');
            if (window.pageYOffset > 50) {
                navbar.style.background = 'rgba(33, 37, 41, 0.98)';
            } else {
                navbar.style.background = 'rgba(33, 37, 41, 0.95)';
            }
        });
    </script>
</body>
</html>