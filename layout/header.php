<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Portal | Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .user-dropdown {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 0.3rem 1rem;
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }
        
        .user-avatar i {
            font-size: 1rem;
            color: white;
        }
        
        .logout-btn {
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #fff;
            background: linear-gradient(135deg, #dc3545, #bb2d3b);
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.2);
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #bb2d3b, #dc3545);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: #fff;
            border-color: transparent;
        }
        
        .navbar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .welcome-text {
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .welcome-text i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .user-dropdown {
                padding: 0.2rem 0.8rem;
            }
            
            .user-avatar {
                width: 28px;
                height: 28px;
            }
            
            .user-avatar i {
                font-size: 0.85rem;
            }
            
            .welcome-text {
                display: none;
            }
            
            .logout-btn {
                padding: 0.3rem 1rem;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .logout-btn {
                padding: 0.25rem 0.8rem;
                font-size: 0.8rem;
            }
            
            .user-avatar {
                width: 28px;
                height: 28px;
                margin-right: 0;
            }
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <!-- Brand with Icon -->
        <a class="navbar-brand fw-bold" href="student_dashboard.php">
            <i class="fas fa-graduation-cap me-2" style="color: #0d6efd;"></i>
            EXAMFLOW
        </a>
        
        <!-- Mobile Toggle (if needed for future menu items) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left side navigation (if needed) -->
            <ul class="navbar-nav me-auto">
                <!-- Add navigation items here if needed -->
            </ul>
            
            <!-- Right side user section -->
            <div class="d-flex align-items-center gap-3">
                <!-- User Info Dropdown/Section -->
                <div class="user-dropdown d-flex align-items-center">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-white welcome-text">
                        <i class="fas fa-hand-peace"></i>
                        <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>
                    </span>
                </div>
                
                <!-- Logout Button -->
                <a href="logout.php" class="logout-btn text-decoration-none">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    <span class="d-none d-sm-inline">Logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content Container -->
<div class="container py-4">
    <!-- Note: The closing div is in footer.php -->
    <!-- Content from individual pages will go here -->