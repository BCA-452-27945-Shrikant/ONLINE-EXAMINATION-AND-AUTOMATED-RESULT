<?php include 'layout/header.php'; ?>

<?php
// Handle Add Subject with Timing & Duration
if(isset($_POST['add_subject'])) {
    $sub_name = $conn->real_escape_string($_POST['sub_name']);
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $duration = intval($_POST['exam_duration']); // Naya field

    $sql = "INSERT INTO subjects (sub_name, start_time, end_time, exam_duration) VALUES ('$sub_name', '$start', '$end', '$duration')";
    if($conn->query($sql)) {
        $success = "Exam scheduled successfully!";
        echo "<script>setTimeout(function() { window.location='manage_subjects.php'; }, 2000);</script>";
    } else {
        $error = "Failed to schedule exam. Please try again.";
    }
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Check if subject has questions/results before deleting
    $check_questions = $conn->query("SELECT COUNT(*) as total FROM questions WHERE sub_id = $id")->fetch_assoc()['total'];
    $check_results = $conn->query("SELECT COUNT(*) as total FROM results WHERE sub_id = $id")->fetch_assoc()['total'];
    
    if($check_questions > 0 || $check_results > 0) {
        $error = "Cannot delete: Subject has $check_questions questions and $check_results results associated.";
    } else {
        if($conn->query("DELETE FROM subjects WHERE sub_id = $id")) {
            $success = "Subject deleted successfully!";
        } else {
            $error = "Failed to delete subject.";
        }
    }
}
?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="fw-bold mb-1">Manage Scheduled Exams</h3>
                    <p class="text-muted mb-0">Create and manage examination schedules with duration settings</p>
                </div>
            </div>
            <div>
                <button class="btn btn-primary btn-lg rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-calendar-plus me-2"></i> Schedule New Exam
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if(isset($success)): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?php echo $success; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <?php echo $error; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <?php
    $total_subjects = $conn->query("SELECT COUNT(*) as total FROM subjects")->fetch_assoc()['total'];
    $total_duration = $conn->query("SELECT SUM(exam_duration) as total FROM subjects")->fetch_assoc()['total'];
    $avg_duration = $total_subjects > 0 ? round($total_duration / $total_subjects) : 0;
    
    $now = date('Y-m-d H:i:s');
    $active_exams = $conn->query("SELECT COUNT(*) as total FROM subjects WHERE start_time <= '$now' AND end_time >= '$now'")->fetch_assoc()['total'];
    $upcoming_exams = $conn->query("SELECT COUNT(*) as total FROM subjects WHERE start_time > '$now'")->fetch_assoc()['total'];
    ?>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Total Subjects</h6>
                        <h3 class="fw-bold mb-0"><?php echo $total_subjects; ?></h3>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Active Now</h6>
                        <h3 class="fw-bold mb-0"><?php echo $active_exams; ?></h3>
                    </div>
                    <i class="fas fa-play-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Upcoming</h6>
                        <h3 class="fw-bold mb-0"><?php echo $upcoming_exams; ?></h3>
                    </div>
                    <i class="fas fa-hourglass-start fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Avg Duration</h6>
                        <h3 class="fw-bold mb-0"><?php echo $avg_duration; ?> min</h3>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
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
            Examination Schedule
        </h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="searchTable" placeholder="Search subjects...">
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2 text-success"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2 text-primary"></i>Print</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="subjectsTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Subject</th>
                        <th class="py-3">Duration</th>
                        <th class="py-3">Start Window</th>
                        <th class="py-3">End Window</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $res = $conn->query("SELECT * FROM subjects ORDER BY start_time DESC");
                    if($res->num_rows > 0):
                        while($row = $res->fetch_assoc()): 
                            $now = new DateTime();
                            $start = new DateTime($row['start_time']);
                            $end = new DateTime($row['end_time']);
                            
                            // Determine status
                            if($now > $end) {
                                $status = 'ended';
                                $status_badge = 'badge bg-danger bg-opacity-10 text-danger';
                                $status_icon = 'fa-times-circle';
                            } elseif($now >= $start && $now <= $end) {
                                $status = 'active';
                                $status_badge = 'badge bg-success bg-opacity-10 text-success';
                                $status_icon = 'fa-play-circle';
                            } else {
                                $status = 'upcoming';
                                $status_badge = 'badge bg-warning bg-opacity-10 text-warning';
                                $status_icon = 'fa-clock';
                            }
                            
                            // Format duration
                            $duration_hours = floor($row['exam_duration'] / 60);
                            $duration_minutes = $row['exam_duration'] % 60;
                            $duration_text = $duration_hours > 0 ? $duration_hours . 'h ' . $duration_minutes . 'm' : $row['exam_duration'] . ' min';
                    ?>
                    <tr class="subject-row">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo $row['sub_name']; ?></h6>
                                    <small class="text-muted">ID: SUB<?php echo str_pad($row['sub_id'], 3, '0', STR_PAD_LEFT); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="fas fa-hourglass-half text-primary"></i>
                                </div>
                                <span class="fw-bold"><?php echo $row['exam_duration']; ?></span>
                                <span class="text-muted ms-1">minutes</span>
                            </div>
                            <small class="text-muted d-block"><?php echo $duration_text; ?></small>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-calendar-alt text-success me-1"></i>
                                <?php echo date('d M Y', strtotime($row['start_time'])); ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('h:i A', strtotime($row['start_time'])); ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-calendar-alt text-danger me-1"></i>
                                <?php echo date('d M Y', strtotime($row['end_time'])); ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('h:i A', strtotime($row['end_time'])); ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="<?php echo $status_badge; ?> px-3 py-2 rounded-pill">
                                <i class="fas <?php echo $status_icon; ?> me-1"></i>
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="tooltip" title="Edit Subject"
                                        onclick="editSubject(<?php echo $row['sub_id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="tooltip" title="View Questions"
                                        onclick="viewQuestions(<?php echo $row['sub_id']; ?>)">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                                <a href="manage_subjects.php?delete=<?php echo $row['sub_id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   data-bs-toggle="tooltip" title="Delete Subject"
                                   onclick="return confirm('⚠️ Warning: This will permanently delete this subject. Make sure no questions or results are associated.')">
                                   <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-calendar-times fa-4x text-muted opacity-25 mb-3"></i>
                                <h6 class="fw-bold">No Exams Scheduled</h6>
                                <p class="small text-muted mb-3">Get started by scheduling your first examination</p>
                                <button class="btn btn-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fas fa-plus-circle me-2"></i>Schedule Now
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Table Footer -->
    <?php if($res->num_rows > 0): ?>
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing <span class="fw-bold"><?php echo $res->num_rows; ?></span> subjects
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

<!-- Add Subject Modal (Enhanced) -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calendar-plus me-2"></i>Schedule New Examination
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <!-- Subject Name -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-book text-primary me-2"></i>Subject Name
                    </label>
                    <input type="text" name="sub_name" class="form-control form-control-lg" 
                           placeholder="e.g. Advanced Java Programming" required>
                    <div class="form-text">Enter the full name of the subject/exam</div>
                </div>

                <!-- Exam Duration -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-hourglass-half text-primary me-2"></i>Exam Duration
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-clock text-primary"></i>
                        </span>
                        <input type="number" name="exam_duration" class="form-control form-control-lg" 
                               placeholder="e.g. 30" min="1" max="300" required>
                        <span class="input-group-text bg-light">minutes</span>
                    </div>
                    <div class="form-text">Set the time allowed for students to complete the exam</div>
                </div>

                <!-- Date/Time Selection -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fas fa-play text-success me-2"></i>Start Time
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-check text-success"></i>
                            </span>
                            <input type="datetime-local" name="start_time" class="form-control" id="startTime" required>
                        </div>
                        <small class="text-muted">When the exam becomes available</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fas fa-stop text-danger me-2"></i>End Time
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-times text-danger"></i>
                            </span>
                            <input type="datetime-local" name="end_time" class="form-control" id="endTime" required>
                        </div>
                        <small class="text-muted">When the exam closes</small>
                    </div>
                </div>

                <!-- Duration Preview -->
                <div class="alert alert-info bg-opacity-10 border-0 mt-4" id="durationPreview" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Schedule Summary:</strong>
                            <br>
                            <span id="scheduleSummary"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" name="add_subject" class="btn btn-primary rounded-pill px-5">
                    <i class="fas fa-check-circle me-2"></i>Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Custom CSS -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.subject-row {
    transition: all 0.2s ease;
}

.subject-row:hover {
    background-color: rgba(13, 110, 253, 0.02);
    transform: translateX(5px);
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
}

/* Modal Animation */
.modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.2s ease;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

/* Status Badge Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.badge.bg-success.bg-opacity-10 {
    animation: pulse 2s infinite;
}

/* Search Input */
#searchTable {
    border-radius: 50px 0 0 50px;
    border-right: none;
}

.input-group-text {
    border-radius: 0 50px 50px 0;
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
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 50px !important;
        margin: 2px 0;
    }
    
    .table td {
        min-width: 150px;
    }
}

/* Duration Badge */
.badge.bg-info {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
// Search functionality
document.getElementById('searchTable')?.addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll('#subjectsTable tbody tr');
    
    rows.forEach(function(row) {
        var subjectName = row.querySelector('td:first-child h6')?.textContent.toLowerCase();
        if(subjectName && subjectName.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Duration and schedule preview in modal
document.getElementById('startTime')?.addEventListener('change', updateScheduleSummary);
document.getElementById('endTime')?.addEventListener('change', updateScheduleSummary);
document.querySelector('input[name="exam_duration"]')?.addEventListener('input', updateScheduleSummary);

function updateScheduleSummary() {
    var start = document.getElementById('startTime')?.value;
    var end = document.getElementById('endTime')?.value;
    var duration = document.querySelector('input[name="exam_duration"]')?.value;
    
    if(start && end && duration) {
        var startDate = new Date(start);
        var endDate = new Date(end);
        
        if(endDate > startDate) {
            var diff = endDate - startDate;
            var days = Math.floor(diff / (1000 * 60 * 60 * 24));
            var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            var summary = `Duration: ${duration} minutes | Exam window: ${days} days ${hours} hours`;
            document.getElementById('scheduleSummary').textContent = summary;
            document.getElementById('durationPreview').style.display = 'block';
        }
    }
}

// Action functions
function editSubject(id) {
    window.location.href = 'edit_subject.php?id=' + id;
}

function viewQuestions(id) {
    window.location.href = 'view_questions.php?sub_id=' + id;
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Validate end time is after start time
document.querySelector('form').addEventListener('submit', function(e) {
    var start = new Date(document.getElementById('startTime').value);
    var end = new Date(document.getElementById('endTime').value);
    var duration = parseInt(document.querySelector('input[name="exam_duration"]').value);
    
    if(end <= start) {
        e.preventDefault();
        alert('End time must be after start time!');
    }
    
    if(duration < 1) {
        e.preventDefault();
        alert('Duration must be at least 1 minute!');
    }
});
</script>

<?php include 'layout/footer.php'; ?>