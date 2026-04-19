<?php include 'layout/header.php'; ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="fw-bold mb-1">Student Performance Reports</h3>
                    <p class="text-muted mb-0">Track and analyze student examination results</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <!-- Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><h6 class="dropdown-header fw-bold">Filter by Status</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="filterReports('all')">All Reports</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterReports('passed')"><span class="badge bg-success me-2">&nbsp;</span>Passed</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterReports('failed')"><span class="badge bg-danger me-2">&nbsp;</span>Failed</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header fw-bold">Date Range</h6></li>
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                    </ul>
                </div>
                
                <!-- Print Button -->
                <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 shadow-sm">
                    <i class="fas fa-print me-2"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats Cards -->
<div class="row g-3 mb-4">
    <?php 
    $total_results = $conn->query("SELECT COUNT(*) as total FROM results")->fetch_assoc()['total'];
    $passed_count = $conn->query("SELECT COUNT(*) as total FROM results WHERE total_score >= 5")->fetch_assoc()['total'];
    $failed_count = $total_results - $passed_count;
    $pass_rate = $total_results > 0 ? round(($passed_count / $total_results) * 100) : 0;
    $avg_score = $conn->query("SELECT AVG(total_score) as avg FROM results")->fetch_assoc()['avg'];
    ?>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card bg-primary text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Total Attempts</h6>
                        <h3 class="fw-bold mb-0"><?php echo $total_results; ?></h3>
                    </div>
                    <i class="fas fa-file-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card bg-success text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Passed</h6>
                        <h3 class="fw-bold mb-0"><?php echo $passed_count; ?></h3>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
                <div class="mt-2 small opacity-75">
                    <i class="fas fa-arrow-up me-1"></i> <?php echo $pass_rate; ?>% pass rate
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card bg-danger text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Failed</h6>
                        <h3 class="fw-bold mb-0"><?php echo $failed_count; ?></h3>
                    </div>
                    <i class="fas fa-times-circle fa-3x opacity-50"></i>
                </div>
                <div class="mt-2 small opacity-75">
                    <i class="fas fa-arrow-down me-1"></i> <?php echo 100 - $pass_rate; ?>% fail rate
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card bg-info text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Average Score</h6>
                        <h3 class="fw-bold mb-0"><?php echo $avg_score ? number_format($avg_score, 1) : '0'; ?></h3>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
                <div class="mt-2 small opacity-75">
                    out of 10 marks
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card with Table -->
<div class="card border-0 shadow-lg">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-list-alt text-primary me-2"></i>
            Detailed Results
        </h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="searchTable" placeholder="Search by name, email, subject...">
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF Report</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2 text-success"></i>Excel Sheet</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2 text-primary"></i>CSV Format</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reportsTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Student Information</th>
                        <th class="py-3">Exam Details</th>
                        <th class="py-3 text-center">Score</th>
                        <th class="py-3 text-center">Percentage</th>
                        <th class="py-3">Completion Date</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="pe-4 py-3 text-center">Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // SQL to join results with users and subjects to get names instead of IDs
                    $sql = "SELECT r.*, u.full_name, u.email, u.user_id, s.sub_name 
                            FROM results r 
                            JOIN users u ON r.user_id = u.user_id 
                            JOIN subjects s ON r.sub_id = s.sub_id 
                            ORDER BY r.date_time DESC";
                    
                    $res = $conn->query($sql);
                    
                    if($res->num_rows > 0):
                        while($row = $res->fetch_assoc()):
                            // Calculate percentage (assuming max score is 10)
                            $percentage = ($row['total_score'] / 10) * 100;
                            $passed = $row['total_score'] >= 5;
                            $status_class = $passed ? 'success' : 'danger';
                            $status_text = $passed ? 'Passed' : 'Failed';
                            $status_icon = $passed ? 'fa-check-circle' : 'fa-times-circle';
                            
                            // Grade based on percentage
                            if($percentage >= 80) $grade = 'A';
                            elseif($percentage >= 60) $grade = 'B';
                            elseif($percentage >= 40) $grade = 'C';
                            else $grade = 'F';
                    ?>
                    <tr class="report-row" data-status="<?php echo $status_text; ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="student-avatar bg-<?php echo $status_class; ?> bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-user text-<?php echo $status_class; ?>"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo $row['full_name']; ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope fa-xs me-1"></i><?php echo $row['email']; ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book text-primary me-2"></i>
                                <div>
                                    <span class="fw-bold"><?php echo $row['sub_name']; ?></span>
                                    <br>
                                    <small class="text-muted">ID: EX<?php echo str_pad($row['res_id'], 4, '0', STR_PAD_LEFT); ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="score-circle bg-<?php echo $status_class; ?> bg-opacity-10 rounded-circle p-2 d-inline-block" style="width: 50px; height: 50px;">
                                <span class="fw-bold text-<?php echo $status_class; ?>"><?php echo $row['total_score']; ?></span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="progress w-75 me-2" style="height: 6px;">
                                    <div class="progress-bar bg-<?php echo $status_class; ?>" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <span class="small fw-bold text-<?php echo $status_class; ?>"><?php echo round($percentage); ?>%</span>
                            </div>
                            <small class="text-muted">Grade <?php echo $grade; ?></small>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-calendar-alt text-primary me-1 fa-sm"></i>
                                <?php echo date('d M Y', strtotime($row['date_time'])); ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('h:i A', strtotime($row['date_time'])); ?>
                                </small>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo $status_class; ?> bg-opacity-10 text-<?php echo $status_class; ?> px-3 py-2 rounded-pill">
                                <i class="fas <?php echo $status_icon; ?> me-1"></i>
                                <?php echo $status_text; ?>
                            </span>
                        </td>
                        <td class="pe-4 text-center">
                            <a href="../print_result.php?res_id=<?php echo $row['res_id']; ?>" target="_blank" 
                               class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="tooltip" title="Download Certificate">
                                <i class="fas fa-file-pdf me-1"></i> Certificate
                            </a>
                            <button class="btn btn-sm btn-outline-info rounded-circle ms-1" 
                                    data-bs-toggle="tooltip" title="View Details"
                                    onclick="viewDetails(<?php echo $row['res_id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-inbox fa-4x text-muted opacity-25 mb-3"></i>
                                <h6 class="fw-bold">No Results Found</h6>
                                <p class="small text-muted mb-0">There are no examination results in the database yet.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Table Footer with Pagination -->
    <?php if($res->num_rows > 0): ?>
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing <span class="fw-bold"><?php echo $res->num_rows; ?></span> results
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
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
    <?php endif; ?>
</div>

<!-- Performance Insights Card -->
<?php if($res->num_rows > 0): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-chart-pie text-primary me-2"></i>Performance Distribution
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mb-2">
                                <i class="fas fa-star me-1"></i> A Grade
                            </span>
                            <h4 class="fw-bold mb-0">
                                <?php 
                                $a_grade = $conn->query("SELECT COUNT(*) as total FROM results WHERE (total_score / 10 * 100) >= 80")->fetch_assoc()['total'];
                                echo $a_grade;
                                ?>
                            </h4>
                            <small class="text-muted">students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 mb-2">
                                <i class="fas fa-star me-1"></i> B Grade
                            </span>
                            <h4 class="fw-bold mb-0">
                                <?php 
                                $b_grade = $conn->query("SELECT COUNT(*) as total FROM results WHERE (total_score / 10 * 100) BETWEEN 60 AND 79")->fetch_assoc()['total'];
                                echo $b_grade;
                                ?>
                            </h4>
                            <small class="text-muted">students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 mb-2">
                                <i class="fas fa-star me-1"></i> C Grade
                            </span>
                            <h4 class="fw-bold mb-0">
                                <?php 
                                $c_grade = $conn->query("SELECT COUNT(*) as total FROM results WHERE (total_score / 10 * 100) BETWEEN 40 AND 59")->fetch_assoc()['total'];
                                echo $c_grade;
                                ?>
                            </h4>
                            <small class="text-muted">students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 mb-2">
                                <i class="fas fa-star me-1"></i> F Grade
                            </span>
                            <h4 class="fw-bold mb-0">
                                <?php 
                                $f_grade = $conn->query("SELECT COUNT(*) as total FROM results WHERE (total_score / 10 * 100) < 40")->fetch_assoc()['total'];
                                echo $f_grade;
                                ?>
                            </h4>
                            <small class="text-muted">students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-trophy text-primary me-2"></i>Top Performers
                </h6>
                <div class="list-group list-group-flush">
                    <?php 
                    $top_performers = $conn->query("
                        SELECT u.full_name, r.total_score, s.sub_name 
                        FROM results r 
                        JOIN users u ON r.user_id = u.user_id 
                        JOIN subjects s ON r.sub_id = s.sub_id 
                        ORDER BY r.total_score DESC 
                        LIMIT 5
                    ");
                    
                    while($top = $top_performers->fetch_assoc()):
                    ?>
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning rounded-circle p-2 text-white" style="width: 35px; height: 35px;">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-0"><?php echo $top['full_name']; ?></h6>
                                <small class="text-muted"><?php echo $top['sub_name']; ?></small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary"><?php echo $top['total_score']; ?>/10</span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Custom CSS -->
<style>
.stat-card {
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transition: all 0.5s ease;
}

.stat-card:hover::after {
    transform: scale(1.5);
}

.report-row {
    transition: all 0.2s ease;
}

.report-row:hover {
    background-color: rgba(13, 110, 253, 0.02);
    transform: translateX(5px);
}

.score-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Print Styles */
@media print {
    .sidebar, 
    .btn, 
    .navbar,
    .stat-card,
    .dropdown,
    .input-group,
    .card-footer,
    .pagination,
    #performanceInsights {
        display: none !important;
    }
    
    .main-content {
        width: 100% !important;
        margin: 0 !important;
        padding: 20px !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
    
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    
    .table th,
    .table td {
        border: 1px solid #dee2e6 !important;
        padding: 8px !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: transparent !important;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-2 {
        width: 100%;
    }
    
    #searchTable {
        width: 100% !important;
    }
    
    .table td {
        min-width: 150px;
    }
    
    .progress.w-75 {
        width: 50px !important;
    }
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
// Search functionality
document.getElementById('searchTable').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll('#reportsTable tbody tr');
    
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if(text.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Filter by status
function filterReports(status) {
    var rows = document.querySelectorAll('#reportsTable tbody tr');
    
    rows.forEach(function(row) {
        if(status === 'all') {
            row.style.display = '';
        } else {
            var rowStatus = row.getAttribute('data-status').toLowerCase();
            if(rowStatus === status.toLowerCase()) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// View details function
function viewDetails(resId) {
    window.location.href = 'view_result_details.php?res_id=' + resId;
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Export functionality (placeholder)
document.querySelectorAll('.dropdown-item').forEach(function(item) {
    item.addEventListener('click', function(e) {
        if(this.querySelector('.fa-file-pdf') || 
           this.querySelector('.fa-file-excel') || 
           this.querySelector('.fa-file-csv')) {
            e.preventDefault();
            alert('Export feature will be available soon!');
        }
    });
});
</script>

<?php include 'layout/footer.php'; ?>