<?php include 'layout/header.php'; ?>

<?php
// --- 1. SINGLE QUESTION ADD LOGIC ---
if(isset($_POST['add_que'])) {
    $sub_id = $_POST['sub_id'];
    $que = $conn->real_escape_string($_POST['question']);
    $a = $conn->real_escape_string($_POST['opt_a']);
    $b = $conn->real_escape_string($_POST['opt_b']);
    $c = $conn->real_escape_string($_POST['opt_c']);
    $d = $conn->real_escape_string($_POST['opt_d']);
    $ans = $_POST['correct_ans'];

    $sql = "INSERT INTO questions (sub_id, question, option_a, option_b, option_c, option_d, ans_correct) 
            VALUES ('$sub_id', '$que', '$a', '$b', '$c', '$d', '$ans')";
    
    if($conn->query($sql)) {
        $success = "Question added successfully!";
    } else {
        $error = "Failed to add question: " . $conn->error;
    }
}

// --- 2. BULK CSV IMPORT LOGIC ---
if(isset($_POST['import_csv'])) {
    $sub_id = $_POST['sub_id'];
    $filename = $_FILES["csv_file"]["tmp_name"];

    if($_FILES["csv_file"]["size"] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row
        $count = 0;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if(!empty($column[0])) {
                $que = $conn->real_escape_string($column[0]);
                $a = $conn->real_escape_string($column[1]);
                $b = $conn->real_escape_string($column[2]);
                $c = $conn->real_escape_string($column[3]);
                $d = $conn->real_escape_string($column[4]);
                $ans = strtoupper(trim($column[5]));

                $conn->query("INSERT INTO questions (sub_id, question, option_a, option_b, option_c, option_d, ans_correct) 
                             VALUES ('$sub_id', '$que', '$a', '$b', '$c', '$d', '$ans')");
                $count++;
            }
        }
        fclose($file);
        $success = "Successfully imported $count questions via CSV!";
    }
}
?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-question-circle fa-2x text-primary"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h3 class="fw-bold mb-1">Question Bank Management</h3>
                <p class="text-muted mb-0">Create, Bulk Upload, and Manage Examination Questions</p>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if(isset($success)): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?php echo $success; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <?php echo $error; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <?php
    $total_questions = $conn->query("SELECT COUNT(*) as total FROM questions")->fetch_assoc()['total'];
    $total_subjects = $conn->query("SELECT COUNT(*) as total FROM subjects")->fetch_assoc()['total'];
    $avg_per_subject = $total_subjects > 0 ? round($total_questions / $total_subjects, 1) : 0;
    ?>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Total Questions</h6>
                        <h3 class="fw-bold mb-0"><?php echo $total_questions; ?></h3>
                    </div>
                    <i class="fas fa-question-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Active Subjects</h6>
                        <h3 class="fw-bold mb-0"><?php echo $total_subjects; ?></h3>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small mb-1 opacity-75">Avg per Subject</h6>
                        <h3 class="fw-bold mb-0"><?php echo $avg_per_subject; ?></h3>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column - Forms -->
    <div class="col-lg-4">
        <!-- Single Question Form -->
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-plus-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0">Add Single Question</h5>
                        <small class="opacity-75">Create one question at a time</small>
                    </div>
                </div>
            </div>
            
            <form method="POST" class="card-body p-4">
                <!-- Subject Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-book-open text-primary me-2"></i>Select Subject
                    </label>
                    <select name="sub_id" class="form-select form-select-lg" required>
                        <option value="" disabled selected>-- Choose a subject --</option>
                        <?php 
                        $subs = $conn->query("SELECT * FROM subjects ORDER BY sub_name");
                        while($s = $subs->fetch_assoc()): 
                        ?>
                        <option value="<?php echo $s['sub_id']; ?>">
                            <?php echo $s['sub_name']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Question Input -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-question text-primary me-2"></i>Question Text
                    </label>
                    <textarea name="question" class="form-control" rows="3" 
                              placeholder="Enter your question here..." required></textarea>
                </div>

                <!-- Options Grid -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-list-ol text-primary me-2"></i>Answer Options
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">A</span>
                                <input type="text" name="opt_a" class="form-control" placeholder="Option A" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">B</span>
                                <input type="text" name="opt_b" class="form-control" placeholder="Option B" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-white">C</span>
                                <input type="text" name="opt_c" class="form-control" placeholder="Option C" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white">D</span>
                                <input type="text" name="opt_d" class="form-control" placeholder="Option D" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Correct Answer -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-check-circle text-success me-2"></i>Correct Answer
                    </label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_ans" value="A" id="ansA" required>
                            <label class="form-check-label" for="ansA">
                                <span class="badge bg-primary">A</span> Option A
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_ans" value="B" id="ansB">
                            <label class="form-check-label" for="ansB">
                                <span class="badge bg-success">B</span> Option B
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_ans" value="C" id="ansC">
                            <label class="form-check-label" for="ansC">
                                <span class="badge bg-warning">C</span> Option C
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_ans" value="D" id="ansD">
                            <label class="form-check-label" for="ansD">
                                <span class="badge bg-danger">D</span> Option D
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="add_que" class="btn btn-primary w-100 rounded-pill py-2">
                    <i class="fas fa-save me-2"></i>Save Question
                </button>
            </form>
        </div>

        <!-- Bulk Import Form -->
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-success text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-csv fa-2x me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0">Bulk Import CSV</h5>
                        <small class="opacity-75">Upload multiple questions at once</small>
                    </div>
                </div>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="card-body p-4">
                <!-- Subject Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-book-open text-success me-2"></i>Select Subject
                    </label>
                    <select name="sub_id" class="form-select form-select-lg" required>
                        <option value="" disabled selected>-- Choose a subject --</option>
                        <?php 
                        $subs = $conn->query("SELECT * FROM subjects ORDER BY sub_name");
                        while($s = $subs->fetch_assoc()): 
                        ?>
                        <option value="<?php echo $s['sub_id']; ?>">
                            <?php echo $s['sub_name']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-upload text-success me-2"></i>Choose CSV File
                    </label>
                    <div class="upload-area p-4 text-center rounded-3 border-2 border-dashed" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3"></i>
                        <p class="mb-2">Drag & drop or click to upload</p>
                        <input type="file" name="csv_file" id="csvFile" class="d-none" accept=".csv" required>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill" onclick="document.getElementById('csvFile').click()">
                            Select CSV File
                        </button>
                        <div id="fileName" class="small text-muted mt-2"></div>
                    </div>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        CSV format: Question, Option A, Option B, Option C, Option D, Correct Answer (A/B/C/D)
                    </div>
                </div>

                <!-- Sample CSV Link -->
                <div class="mb-4">
                    <a href="#" class="text-success text-decoration-none small">
                        <i class="fas fa-download me-1"></i> Download Sample CSV Template
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="import_csv" class="btn btn-success w-100 rounded-pill py-2">
                    <i class="fas fa-upload me-2"></i>Upload & Import
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column - Question Bank -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-database text-primary me-2"></i>
                    Question Bank
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchTable" placeholder="Search questions...">
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="fas fa-question-circle me-1"></i>
                        <?php echo $total_questions; ?> Total
                    </span>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="questionsTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Subject</th>
                                <th class="py-3">Question Preview</th>
                                <th class="py-3 text-center">Correct</th>
                                <th class="py-3 text-center">Options</th>
                                <th class="pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $qs = $conn->query("SELECT q.*, s.sub_name FROM questions q JOIN subjects s ON q.sub_id = s.sub_id ORDER BY q.q_id DESC LIMIT 20");
                            if($qs->num_rows > 0):
                                while($row = $qs->fetch_assoc()): 
                            ?>
                            <tr class="question-row">
                                <td class="ps-4">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                        <?php echo $row['sub_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-question-circle text-muted me-2"></i>
                                        <div class="text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($row['question']); ?>">
                                            <?php echo htmlspecialchars($row['question']); ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        ID: Q<?php echo str_pad($row['q_id'], 4, '0', STR_PAD_LEFT); ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-circle p-2" style="width: 35px;">
                                        <?php echo $row['ans_correct']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">A</span>
                                        <span class="badge bg-success bg-opacity-10 text-success">B</span>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">C</span>
                                        <span class="badge bg-danger bg-opacity-10 text-danger">D</span>
                                    </div>
                                </td>
                                <td class="pe-4 text-end">
                                    <button onclick="editQuestion(<?php echo $row['q_id']; ?>)" 
                                            class="btn btn-sm btn-outline-primary rounded-circle me-1"
                                            data-bs-toggle="tooltip" title="Edit Question">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteQuestion(<?php echo $row['q_id']; ?>)" 
                                            class="btn btn-sm btn-outline-danger rounded-circle"
                                            data-bs-toggle="tooltip" title="Delete Question">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-4x text-muted opacity-25 mb-3"></i>
                                        <h6 class="fw-bold">No Questions Found</h6>
                                        <p class="small text-muted">Start by adding your first question</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Table Footer -->
            <?php if($qs->num_rows > 0): ?>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Showing last 20 questions
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
    </div>
</div>

<!-- Custom CSS -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

.form-label {
    letter-spacing: 0.5px;
}

.btn-outline-primary:hover i,
.btn-outline-danger:hover i {
    color: white;
}

/* Upload Area */
.upload-area {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #198754;
    background: rgba(25, 135, 84, 0.02);
}

.upload-area:hover i {
    transform: translateY(-5px);
}

.upload-area i {
    transition: all 0.3s ease;
}

/* Question Row */
.question-row {
    transition: all 0.2s ease;
}

.question-row:hover {
    background-color: rgba(13, 110, 253, 0.02);
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
    
    .table td {
        min-width: 150px;
    }
    
    .upload-area {
        padding: 1rem !important;
    }
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
// Search functionality
document.getElementById('searchTable')?.addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll('#questionsTable tbody tr');
    
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if(text.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// File upload display
document.getElementById('csvFile')?.addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name;
    var fileNameDisplay = document.getElementById('fileName');
    if(fileName) {
        fileNameDisplay.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>' + fileName;
    } else {
        fileNameDisplay.innerHTML = '';
    }
});

// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
if(uploadArea) {
    uploadArea.addEventListener('click', function() {
        document.getElementById('csvFile').click();
    });
    
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#198754';
        this.style.backgroundColor = 'rgba(25, 135, 84, 0.02)';
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '#f8f9fa';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '#f8f9fa';
        
        const file = e.dataTransfer.files[0];
        if(file && file.type === 'text/csv') {
            document.getElementById('csvFile').files = e.dataTransfer.files;
            document.getElementById('fileName').innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>' + file.name;
        } else {
            alert('Please upload a CSV file');
        }
    });
}

// Edit and Delete functions
function editQuestion(id) {
    window.location.href = 'edit_question.php?id=' + id;
}

function deleteQuestion(id) {
    if(confirm('⚠️ Warning: This question will be permanently removed. This action cannot be undone!')) {
        window.location.href = 'delete_question.php?id=' + id;
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include 'layout/footer.php'; ?>