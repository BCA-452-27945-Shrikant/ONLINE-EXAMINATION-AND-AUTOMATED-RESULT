<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | ExamFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; border-bottom: 1px solid #2c3136; }
        .sidebar a:hover { background: #343a40; color: #fff; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-0 d-none d-md-block">
            <div class="p-3 text-white text-center"><h5>ADMIN DASHBOARD</h5></div>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="manage_subjects.php"><i class="fas fa-book me-2"></i> Subjects</a>
            <a href="manage_questions.php"><i class="fas fa-question-circle me-2"></i> Questions</a>
            <a href="view_results.php"><i class="fas fa-poll me-2"></i> Results</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fas fa-power-off me-2"></i> Logout</a>
        </div>
        <div class="col-md-10 main-content p-4">