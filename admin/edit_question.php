<?php include 'layout/header.php'; ?>

<?php
if(!isset($_GET['id'])) { header("Location: manage_questions.php"); exit(); }
$id = intval($_GET['id']);

// Fetch Current Question Data
$res = $conn->query("SELECT * FROM questions WHERE q_id = $id");
$data = $res->fetch_assoc();

if(!$data) {
    echo "<div class='alert alert-danger'>Question not found!</div>";
    include 'layout/footer.php';
    exit();
}

if(isset($_POST['update_que'])) {
    $sub_id = $_POST['sub_id'];
    $que = $conn->real_escape_string($_POST['question']);
    $a = $conn->real_escape_string($_POST['opt_a']);
    $b = $conn->real_escape_string($_POST['opt_b']);
    $c = $conn->real_escape_string($_POST['opt_c']);
    $d = $conn->real_escape_string($_POST['opt_d']);
    $ans = $_POST['correct_ans'];

    $sql = "UPDATE questions SET 
            sub_id='$sub_id', question='$que', 
            option_a='$a', option_b='$b', option_c='$c', option_d='$d', 
            ans_correct='$ans' 
            WHERE q_id=$id";
    
    if($conn->query($sql)) {
        $success = "Question updated successfully!";
        echo "<script>setTimeout(function() { window.location='manage_questions.php'; }, 2000);</script>";
    } else {
        $error = "Failed to update question. Please try again.";
    }
}
?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-edit fa-2x text-primary"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h3 class="fw-bold mb-1">Edit Question</h3>
                <p class="text-muted mb-0">Question ID: <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">Q<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></span></p>
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

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Main Edit Card -->
        <div class="card border-0 shadow-lg overflow-hidden">
            <!-- Card Header with Gradient -->
            <div class="card-header bg-gradient-primary text-white border-0 py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-question-circle fa-2x text-white"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Edit Question Details</h5>
                        <p class="small mb-0 opacity-75">Update the question, options, and correct answer below</p>
                    </div>
                </div>
            </div>

            <form method="POST" class="card-body p-4">
                <!-- Subject Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fas fa-book-open text-primary me-2"></i>Subject
                        </label>
                        <select name="sub_id" class="form-select form-select-lg" required>
                            <option value="" disabled>-- Select a subject --</option>
                            <?php 
                            $subs = $conn->query("SELECT * FROM subjects ORDER BY sub_name");
                            while($s = $subs->fetch_assoc()) {
                                $selected = ($s['sub_id'] == $data['sub_id']) ? "selected" : "";
                                echo "<option value='".$s['sub_id']."' $selected>".$s['sub_name']."</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Choose the subject this question belongs to</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fas fa-tag text-primary me-2"></i>Question ID
                        </label>
                        <div class="bg-light p-3 rounded-3">
                            <span class="fw-bold text-primary fs-5">Q<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></span>
                            <span class="text-muted ms-2">• Created: <?php echo isset($data['created_at']) ? date('d M Y', strtotime($data['created_at'])) : 'N/A'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-question text-primary me-2"></i>Question
                    </label>
                    <div class="position-relative">
                        <textarea name="question" class="form-control form-control-lg" rows="4" 
                                  placeholder="Enter your question here..." required><?php echo htmlspecialchars($data['question']); ?></textarea>
                        <div class="position-absolute bottom-0 end-0 mb-2 me-3">
                            <span class="small text-muted question-counter">0/500</span>
                        </div>
                    </div>
                    <div class="form-text">Write the question clearly and concisely</div>
                </div>

                <!-- Options Section -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-list-ol text-primary me-2"></i>Answer Options
                    </label>
                    
                    <div class="row g-3">
                        <!-- Option A -->
                        <div class="col-md-6">
                            <div class="option-card" data-option="A">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white fw-bold">A</span>
                                    <input type="text" name="opt_a" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($data['option_a']); ?>" 
                                           placeholder="Option A" required>
                                </div>
                                <div class="mt-2 ms-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        First option
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Option B -->
                        <div class="col-md-6">
                            <div class="option-card" data-option="B">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white fw-bold">B</span>
                                    <input type="text" name="opt_b" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($data['option_b']); ?>" 
                                           placeholder="Option B" required>
                                </div>
                                <div class="mt-2 ms-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Second option
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Option C -->
                        <div class="col-md-6">
                            <div class="option-card" data-option="C">
                                <div class="input-group">
                                    <span class="input-group-text bg-warning text-white fw-bold">C</span>
                                    <input type="text" name="opt_c" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($data['option_c']); ?>" 
                                           placeholder="Option C" required>
                                </div>
                                <div class="mt-2 ms-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Third option
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Option D -->
                        <div class="col-md-6">
                            <div class="option-card" data-option="D">
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white fw-bold">D</span>
                                    <input type="text" name="opt_d" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($data['option_d']); ?>" 
                                           placeholder="Option D" required>
                                </div>
                                <div class="mt-2 ms-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Fourth option
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Correct Answer Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-check-circle text-primary me-2"></i>Correct Answer
                    </label>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="correct-option-card <?php echo ($data['ans_correct'] == 'A') ? 'selected' : ''; ?>" 
                                 onclick="selectCorrectAnswer('A')">
                                <input type="radio" name="correct_ans" value="A" id="ansA" 
                                       <?php echo ($data['ans_correct'] == 'A') ? 'checked' : ''; ?> class="d-none">
                                <div class="p-3 border rounded-3 text-center">
                                    <span class="badge bg-primary mb-2">Option A</span>
                                    <i class="fas fa-check-circle text-success check-icon <?php echo ($data['ans_correct'] == 'A') ? 'visible' : ''; ?>"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="correct-option-card <?php echo ($data['ans_correct'] == 'B') ? 'selected' : ''; ?>" 
                                 onclick="selectCorrectAnswer('B')">
                                <input type="radio" name="correct_ans" value="B" id="ansB" 
                                       <?php echo ($data['ans_correct'] == 'B') ? 'checked' : ''; ?> class="d-none">
                                <div class="p-3 border rounded-3 text-center">
                                    <span class="badge bg-success mb-2">Option B</span>
                                    <i class="fas fa-check-circle text-success check-icon <?php echo ($data['ans_correct'] == 'B') ? 'visible' : ''; ?>"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="correct-option-card <?php echo ($data['ans_correct'] == 'C') ? 'selected' : ''; ?>" 
                                 onclick="selectCorrectAnswer('C')">
                                <input type="radio" name="correct_ans" value="C" id="ansC" 
                                       <?php echo ($data['ans_correct'] == 'C') ? 'checked' : ''; ?> class="d-none">
                                <div class="p-3 border rounded-3 text-center">
                                    <span class="badge bg-warning mb-2">Option C</span>
                                    <i class="fas fa-check-circle text-success check-icon <?php echo ($data['ans_correct'] == 'C') ? 'visible' : ''; ?>"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="correct-option-card <?php echo ($data['ans_correct'] == 'D') ? 'selected' : ''; ?>" 
                                 onclick="selectCorrectAnswer('D')">
                                <input type="radio" name="correct_ans" value="D" id="ansD" 
                                       <?php echo ($data['ans_correct'] == 'D') ? 'checked' : ''; ?> class="d-none">
                                <div class="p-3 border rounded-3 text-center">
                                    <span class="badge bg-danger mb-2">Option D</span>
                                    <i class="fas fa-check-circle text-success check-icon <?php echo ($data['ans_correct'] == 'D') ? 'visible' : ''; ?>"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-eye text-primary me-2"></i>Question Preview
                        </h6>
                        <div class="preview-box p-3 bg-white rounded-3">
                            <p class="fw-bold mb-3"><?php echo htmlspecialchars($data['question']); ?></p>
                            <div class="row g-2">
                                <div class="col-6"><span class="badge bg-primary me-2">A</span> <?php echo htmlspecialchars($data['option_a']); ?></div>
                                <div class="col-6"><span class="badge bg-success me-2">B</span> <?php echo htmlspecialchars($data['option_b']); ?></div>
                                <div class="col-6"><span class="badge bg-warning me-2">C</span> <?php echo htmlspecialchars($data['option_c']); ?></div>
                                <div class="col-6"><span class="badge bg-danger me-2">D</span> <?php echo htmlspecialchars($data['option_d']); ?></div>
                            </div>
                            <div class="mt-2 text-success">
                                <i class="fas fa-check-circle me-1"></i> Correct Answer: Option <?php echo $data['ans_correct']; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 justify-content-between align-items-center">
                    <div>
                        <a href="manage_questions.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-warning btn-lg rounded-pill px-4">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" name="update_que" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                            <i class="fas fa-save me-2"></i>Update Question
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danger Zone (Delete Option) -->
        <div class="card border-0 shadow-sm mt-4 border-start border-danger border-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-bold text-danger mb-1">
                            <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                        </h6>
                        <p class="small text-muted mb-0">Once you delete a question, there is no going back. Please be certain.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="manage_questions.php?delete=<?php echo $id; ?>" 
                           class="btn btn-outline-danger rounded-pill px-4"
                           onclick="return confirm('Are you absolutely sure you want to delete this question? This action cannot be undone!')">
                            <i class="fas fa-trash-alt me-2"></i>Delete Question
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-opacity-20 {
    --bs-bg-opacity: 0.2;
}

/* Option Cards */
.option-card .input-group-text {
    font-weight: bold;
    border: none;
    width: 50px;
    justify-content: center;
}

.option-card input {
    transition: all 0.2s ease;
}

.option-card input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

/* Correct Answer Selection */
.correct-option-card {
    cursor: pointer;
    transition: all 0.2s ease;
}

.correct-option-card .border {
    transition: all 0.2s ease;
    position: relative;
}

.correct-option-card:hover .border {
    border-color: #0d6efd !important;
    background-color: rgba(13, 110, 253, 0.02);
}

.correct-option-card.selected .border {
    border-color: #198754 !important;
    border-width: 2px !important;
    background-color: rgba(25, 135, 84, 0.05);
}

.check-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 1.2rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.check-icon.visible {
    opacity: 1;
}

/* Preview Box */
.preview-box {
    border-left: 4px solid #0d6efd;
    background: white;
}

/* Question Counter */
.question-counter {
    background: rgba(255,255,255,0.9);
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.8rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 > div {
        width: 100%;
    }
    
    .d-flex.gap-3 .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .correct-option-card {
        margin-bottom: 10px;
    }
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
// Question character counter
document.querySelector('textarea[name="question"]').addEventListener('input', function() {
    var counter = document.querySelector('.question-counter');
    var length = this.value.length;
    counter.textContent = length + '/500';
    
    if(length > 500) {
        counter.classList.add('text-danger');
    } else {
        counter.classList.remove('text-danger');
    }
});

// Select correct answer function
function selectCorrectAnswer(option) {
    // Update radio button
    document.getElementById('ans' + option).checked = true;
    
    // Update UI
    document.querySelectorAll('.correct-option-card').forEach(function(card) {
        card.classList.remove('selected');
        card.querySelector('.check-icon').classList.remove('visible');
    });
    
    document.querySelectorAll('.correct-option-card')[option.charCodeAt(0) - 65].classList.add('selected');
    document.querySelectorAll('.correct-option-card')[option.charCodeAt(0) - 65].querySelector('.check-icon').classList.add('visible');
}

// Initialize counter on page load
document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.querySelector('textarea[name="question"]');
    var counter = document.querySelector('.question-counter');
    if(textarea && counter) {
        counter.textContent = textarea.value.length + '/500';
    }
});

// Prevent form resubmission on refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>

<?php include 'layout/footer.php'; ?>