<?php include 'layout/header.php'; ?>

<!-- Page Header with Stats -->
<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="fw-bold mb-2">
            <i class="fas fa-history me-2 text-primary"></i>
            Examination History
        </h3>
        <p class="text-muted">Track your performance across all examinations</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="bg-white p-3 rounded-3 shadow-sm d-inline-block">
            <span class="small text-muted d-block">Total Exams Taken</span>
            <span class="h4 fw-bold text-primary">
                <?php 
                $u_id = $_SESSION['user_id'];
                $count = $conn->query("SELECT COUNT(*) as total FROM results WHERE user_id = $u_id")->fetch_assoc()['total'];
                echo $count;
                ?>
            </span>
        </div>
    </div>
</div>

<!-- History Cards (Mobile View) + Table (Desktop) -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0">
                <i class="fas fa-list-alt me-2 text-primary"></i>
                Exam Records
            </h5>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF Report</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2 text-success"></i>Excel Sheet</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2 text-primary"></i>Print</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-bold">Subject</th>
                        <th class="py-3 text-muted small fw-bold">Score</th>
                        <th class="py-3 text-muted small fw-bold">Percentage</th>
                        <th class="py-3 text-muted small fw-bold">Date & Time</th>
                        <th class="py-3 text-muted small fw-bold">Status</th>
                        <th class="py-3 text-muted small fw-bold text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $history = $conn->query("SELECT r.*, s.sub_name FROM results r JOIN subjects s ON r.sub_id = s.sub_id WHERE r.user_id = $u_id ORDER BY r.date_time DESC");
                    
                    while($row = $history->fetch_assoc()):
                        $total_questions = 10; // Assuming 10 questions
                        $percentage = ($row['total_score'] / $total_questions) * 100;
                        $status = ($percentage >= 40) ? 'Pass' : 'Fail';
                        $status_color = ($percentage >= 40) ? 'success' : 'danger';
                        $status_icon = ($percentage >= 40) ? 'check-circle' : 'exclamation-triangle';
                        
                        // Determine grade based on percentage
                        if($percentage >= 80) $grade = 'A';
                        elseif($percentage >= 60) $grade = 'B';
                        elseif($percentage >= 40) $grade = 'C';
                        else $grade = 'F';
                    ?>
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold"><?php echo $row['sub_name']; ?></div>
                            <small class="text-muted">ID: EX<?php echo str_pad($row['res_id'], 4, '0', STR_PAD_LEFT); ?></small>
                        </td>
                        <td>
                            <span class="fw-bold fs-5"><?php echo $row['total_score']; ?></span>
                            <small class="text-muted">/<?php echo $total_questions; ?></small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress w-75 me-2" style="height: 6px;">
                                    <div class="progress-bar bg-<?php echo $status_color; ?>" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <span class="small fw-bold"><?php echo round($percentage); ?>%</span>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-calendar-alt text-primary me-1 fa-sm"></i>
                            <?php echo date('d M Y', strtotime($row['date_time'])); ?>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1 fa-sm"></i>
                                <?php echo date('h:i A', strtotime($row['date_time'])); ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $status_color; ?> bg-opacity-10 text-<?php echo $status_color; ?> px-3 py-2 rounded-pill">
                                <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                                <?php echo $status; ?> (Grade <?php echo $grade; ?>)
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="print_result.php?res_id=<?php echo $row['res_id']; ?>" 
                               class="btn btn-sm btn-primary rounded-pill px-3"
                               data-bs-toggle="tooltip" title="Download Result">
                                <i class="fas fa-download me-1"></i> PDF
                            </a>
                            <a href="view_result.php?res_id=<?php echo $row['res_id']; ?>" 
                               class="btn btn-sm btn-outline-secondary rounded-pill px-3 ms-2"
                               data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none p-3">
            <?php 
            // Reset pointer to beginning for mobile view
            $history = $conn->query("SELECT r.*, s.sub_name FROM results r JOIN subjects s ON r.sub_id = s.sub_id WHERE r.user_id = $u_id ORDER BY r.date_time DESC");
            
            if($history->num_rows > 0):
                while($row = $history->fetch_assoc()):
                    $total_questions = 10;
                    $percentage = ($row['total_score'] / $total_questions) * 100;
                    $status = ($percentage >= 40) ? 'Pass' : 'Fail';
                    $status_color = ($percentage >= 40) ? 'success' : 'danger';
            ?>
            <div class="card border-0 shadow-sm mb-3 history-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1"><?php echo $row['sub_name']; ?></h6>
                            <small class="text-muted">ID: EX<?php echo str_pad($row['res_id'], 4, '0', STR_PAD_LEFT); ?></small>
                        </div>
                        <span class="badge bg-<?php echo $status_color; ?> bg-opacity-10 text-<?php echo $status_color; ?> px-3 py-2 rounded-pill">
                            <?php echo $status; ?>
                        </span>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Score</small>
                            <span class="fw-bold fs-5"><?php echo $row['total_score']; ?>/<?php echo $total_questions; ?></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Percentage</small>
                            <span class="fw-bold text-<?php echo $status_color; ?>"><?php echo round($percentage); ?>%</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Date & Time</small>
                        <div class="d-flex gap-3">
                            <span><i class="fas fa-calendar-alt text-primary me-1"></i><?php echo date('d M Y', strtotime($row['date_time'])); ?></span>
                            <span><i class="fas fa-clock text-primary me-1"></i><?php echo date('h:i A', strtotime($row['date_time'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="print_result.php?res_id=<?php echo $row['res_id']; ?>" class="btn btn-primary flex-fill rounded-pill">
                            <i class="fas fa-download me-1"></i> PDF
                        </a>
                        <a href="view_result.php?res_id=<?php echo $row['res_id']; ?>" class="btn btn-outline-primary flex-fill rounded-pill">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted opacity-25 mb-3"></i>
                <h6 class="fw-bold">No Exam History</h6>
                <p class="small text-muted">You haven't taken any exams yet.</p>
                <a href="student_dashboard.php" class="btn btn-primary rounded-pill px-4 mt-2">
                    Browse Exams
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Table Footer with Summary -->
    <?php if($history->num_rows > 0): ?>
    <div class="card-footer bg-white border-0 py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle me-1 text-primary"></i>
                    Showing <?php echo $history->num_rows; ?> examination records
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="History pagination">
                    <ul class="pagination pagination-sm justify-content-center justify-content-md-end mb-0">
                        <li class="page-item disabled">
                            <a class="page-link border-0 bg-light text-muted" href="#">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link border-0 bg-primary" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 bg-light text-primary" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 bg-light text-primary" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link border-0 bg-light text-primary" href="#">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Performance Analytics Section (Optional) -->
<?php if($history->num_rows > 0): 
    // Calculate average score
    $avg_score = $conn->query("SELECT AVG(total_score) as avg FROM results WHERE user_id = $u_id")->fetch_assoc()['avg'];
    $avg_percentage = ($avg_score / 10) * 100;
    
    // Get highest score
    $highest = $conn->query("SELECT MAX(total_score) as max FROM results WHERE user_id = $u_id")->fetch_assoc()['max'];
?>
<div class="row mt-4 g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="opacity-75 text-uppercase">Average Score</small>
                        <h3 class="fw-bold mb-0"><?php echo round($avg_percentage, 1); ?>%</h3>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="opacity-75 text-uppercase">Highest Score</small>
                        <h3 class="fw-bold mb-0"><?php echo $highest; ?>/10</h3>
                    </div>
                    <i class="fas fa-trophy fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="opacity-75 text-uppercase">Pass Rate</small>
                        <h3 class="fw-bold mb-0">
                            <?php 
                            $pass_count = $conn->query("SELECT COUNT(*) as pass FROM results WHERE user_id = $u_id AND (total_score / 10 * 100) >= 40")->fetch_assoc()['pass'];
                            $pass_rate = ($pass_count / $history->num_rows) * 100;
                            echo round($pass_rate, 1); ?>%
                        </h3>
                    </div>
                    <i class="fas fa-star fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Custom CSS -->
<style>
.history-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}
.history-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15) !important;
    border-left-color: #0d6efd;
}
.table tbody tr {
    transition: all 0.2s ease;
}
.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.02);
}
.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
.pagination .page-link {
    margin: 0 3px;
    border-radius: 50px !important;
    box-shadow: none;
}
.pagination .page-item.active .page-link {
    box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3);
}
</style>

<?php include 'layout/footer.php'; ?>