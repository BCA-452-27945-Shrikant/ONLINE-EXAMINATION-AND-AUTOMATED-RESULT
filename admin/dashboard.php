<?php include 'layout/header.php'; ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="fw-bold mb-2">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    System Overview
                </h3>
                <p class="text-muted mb-0">Welcome back, Admin! Here's what's happening with your platform.</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-white text-dark shadow-sm border py-2 px-3 rounded-pill">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    <?php echo date('l, d M Y'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-lg stat-card stat-card-primary">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="small text-uppercase fw-bold text-white opacity-75">Total Students</span>
                        <h2 class="fw-bold text-white mb-0 mt-2 display-5">
                            <?php echo $conn->query("SELECT * FROM users")->num_rows; ?>
                        </h2>
                        <span class="small text-white opacity-75">
                            <i class="fas fa-arrow-up me-1"></i>
                            Active learners
                        </span>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate fa-4x text-white opacity-50"></i>
                    </div>
                </div>
                
                <!-- Mini Sparkline -->
                <div class="mt-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-2">
                            <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: 75%"></div>
                            </div>
                        </div>
                        <span class="small text-white">+12%</span>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer Link -->
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="manage_students.php" class="text-white text-decoration-none small">
                    Manage Students <i class="fas fa-arrow-right ms-1 fa-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-lg stat-card stat-card-success">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="small text-uppercase fw-bold text-white opacity-75">Total Subjects</span>
                        <h2 class="fw-bold text-white mb-0 mt-2 display-5">
                            <?php echo $conn->query("SELECT * FROM subjects")->num_rows; ?>
                        </h2>
                        <span class="small text-white opacity-75">
                            <i class="fas fa-book-open me-1"></i>
                            Active examinations
                        </span>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-book fa-4x text-white opacity-50"></i>
                    </div>
                </div>
                
                <!-- Mini Sparkline -->
                <div class="mt-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-2">
                            <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: 45%"></div>
                            </div>
                        </div>
                        <span class="small text-white">+5%</span>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer Link -->
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="manage_subjects.php" class="text-white text-decoration-none small">
                    Manage Subjects <i class="fas fa-arrow-right ms-1 fa-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-lg stat-card stat-card-info">
            <div class="card-body p-4">
                <?php 
                $total_exams_taken = $conn->query("SELECT COUNT(*) as total FROM results")->fetch_assoc()['total'];
                $total_questions = $conn->query("SELECT COUNT(*) as total FROM questions")->fetch_assoc()['total'];
                ?>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="small text-uppercase fw-bold text-white opacity-75">Exams Taken</span>
                        <h2 class="fw-bold text-white mb-0 mt-2 display-5">
                            <?php echo $total_exams_taken; ?>
                        </h2>
                        <span class="small text-white opacity-75">
                            <i class="fas fa-file-alt me-1"></i>
                            Total submissions
                        </span>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-file-signature fa-4x text-white opacity-50"></i>
                    </div>
                </div>
                
                <!-- Mini Stats -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between small text-white">
                        <span><i class="fas fa-question-circle me-1"></i><?php echo $total_questions; ?> Questions</span>
                        <span><i class="fas fa-clock me-1"></i>Avg. 25m</span>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer Link -->
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="view_results.php" class="text-white text-decoration-none small">
                    View Results <i class="fas fa-arrow-right ms-1 fa-sm"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Second Row - Additional Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Pass Rate</h6>
                        <h4 class="fw-bold mb-0">
                            <?php 
                            $total_results = $conn->query("SELECT COUNT(*) as total FROM results")->fetch_assoc()['total'];
                            $passed = $conn->query("SELECT COUNT(*) as total FROM results WHERE (total_score / 10 * 100) >= 40")->fetch_assoc()['total'];
                            $pass_rate = $total_results > 0 ? round(($passed / $total_results) * 100) : 0;
                            echo $pass_rate; ?>%
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-clock fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Avg. Score</h6>
                        <h4 class="fw-bold mb-0">
                            <?php 
                            $avg_score = $conn->query("SELECT AVG(total_score) as avg FROM results")->fetch_assoc()['avg'];
                            echo $avg_score ? round($avg_score, 1) : '0'; ?>/10
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar-check fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Today's Exams</h6>
                        <h4 class="fw-bold mb-0">
                            <?php 
                            $today = date('Y-m-d');
                            $today_exams = $conn->query("SELECT COUNT(*) as total FROM subjects WHERE DATE(start_time) = '$today'")->fetch_assoc()['total'];
                            echo $today_exams;
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-question-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Questions</h6>
                        <h4 class="fw-bold mb-0"><?php echo $total_questions ?? 0; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>
                    Recent Exam Activity
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php 
                    $recent_results = $conn->query("
                        SELECT r.*, u.full_name, s.sub_name 
                        FROM results r 
                        JOIN users u ON r.user_id = u.user_id 
                        JOIN subjects s ON r.sub_id = s.sub_id 
                        ORDER BY r.date_time DESC 
                        LIMIT 5
                    ");
                    
                    if($recent_results->num_rows > 0):
                        while($row = $recent_results->fetch_assoc()): 
                            $percentage = ($row['total_score'] / 10) * 100;
                            $status_color = $percentage >= 40 ? 'success' : 'danger';
                    ?>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-<?php echo $status_color; ?> bg-opacity-10 rounded-circle p-2">
                                    <i class="fas fa-user text-<?php echo $status_color; ?>"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0"><?php echo $row['full_name']; ?></h6>
                                    <small class="text-muted"><?php echo date('h:i A', strtotime($row['date_time'])); ?></small>
                                </div>
                                <p class="small text-muted mb-0">
                                    <?php echo $row['sub_name']; ?> - 
                                    <span class="text-<?php echo $status_color; ?>"><?php echo $row['total_score']; ?>/10</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted small mb-0">No recent activity</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-2 text-center">
                <a href="view_results.php" class="text-decoration-none small">
                    View All Activity <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    Upcoming Exams
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php 
                    $upcoming = $conn->query("
                        SELECT * FROM subjects 
                        WHERE start_time > NOW() 
                        ORDER BY start_time ASC 
                        LIMIT 5
                    ");
                    
                    if($upcoming->num_rows > 0):
                        while($row = $upcoming->fetch_assoc()): 
                            $time_diff = strtotime($row['start_time']) - time();
                            $days = floor($time_diff / (60 * 60 * 24));
                            $hours = floor(($time_diff % (60 * 60 * 24)) / (60 * 60));
                    ?>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0"><?php echo $row['sub_name']; ?></h6>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill">
                                        <?php echo $days > 0 ? $days.'d' : $hours.'h'; ?>
                                    </span>
                                </div>
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d M Y, h:i A', strtotime($row['start_time'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted small mb-0">No upcoming exams</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-2 text-center">
                <a href="manage_subjects.php" class="text-decoration-none small">
                    Schedule New Exam <i class="fas fa-plus-circle ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-bolt me-2 text-primary"></i>
                    Quick Actions
                </h6>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action-item text-center p-3 rounded-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-2 d-inline-block">
                                    <i class="fas fa-user-plus text-primary"></i>
                                </div>
                                <span class="d-block small fw-bold">Add Student</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="manage_subjects.php" class="text-decoration-none">
                            <div class="quick-action-item text-center p-3 rounded-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-2 d-inline-block">
                                    <i class="fas fa-book text-success"></i>
                                </div>
                                <span class="d-block small fw-bold">Add Subject</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="manage_questions.php" class="text-decoration-none">
                            <div class="quick-action-item text-center p-3 rounded-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 mb-2 d-inline-block">
                                    <i class="fas fa-question text-warning"></i>
                                </div>
                                <span class="d-block small fw-bold">Add Questions</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="view_results.php" class="text-decoration-none">
                            <div class="quick-action-item text-center p-3 rounded-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-2 d-inline-block">
                                    <i class="fas fa-chart-bar text-info"></i>
                                </div>
                                <span class="d-block small fw-bold">View Results</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.stat-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
}

.stat-card-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.stat-card-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
}

.stat-card-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
}

.stat-icon {
    transition: all 0.5s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.quick-action-item {
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.quick-action-item:hover {
    background: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15);
}

.quick-action-item:hover .rounded-circle {
    transform: scale(1.1);
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(13, 110, 253, 0.02);
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 2rem;
    }
    
    .stat-card .card-body {
        padding: 1.5rem !important;
    }
    
    .fa-4x {
        font-size: 2.5rem;
    }
}
</style>

<?php include 'layout/footer.php'; ?>