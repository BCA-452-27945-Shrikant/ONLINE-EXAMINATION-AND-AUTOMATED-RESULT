<?php include 'layout/header.php'; ?>

<?php
if(!isset($_GET['id'])) { header("Location: student_dashboard.php"); exit(); }
$sub_id = intval($_GET['id']);
$u_id = $_SESSION['user_id'];

// 1. Prevent re-entry check
$check = $conn->query("SELECT res_id FROM results WHERE user_id = $u_id AND sub_id = $sub_id");
if($check->num_rows > 0) {
    echo "<div class='alert alert-danger text-center shadow-sm border-0 py-4 mt-5'>
            <i class='fas fa-shield-alt fa-3x mb-3 d-block text-danger'></i>
            <h5 class='fw-bold'>Access Denied</h5>
            <p class='mb-0'>You have already completed this examination.</p>
            <a href='student_dashboard.php' class='btn btn-outline-danger btn-sm rounded-pill mt-3 px-4'>
                <i class='fas fa-arrow-left me-2'></i>Return to Dashboard
            </a>
          </div>";
    include 'layout/footer.php';
    exit();
}

// 2. Fetch Subject & Duration
$subject_res = $conn->query("SELECT * FROM subjects WHERE sub_id = $sub_id");
$subject = $subject_res->fetch_assoc();
$exam_duration = isset($subject['exam_duration']) ? $subject['exam_duration'] : 10; // Default 10 if not set

// 3. Fetch Questions
$questions_query = $conn->query("SELECT * FROM questions WHERE sub_id = $sub_id ORDER BY RAND()");
$total_questions = $questions_query->num_rows;

if($total_questions == 0) {
    echo "<div class='alert alert-warning text-center mt-5'>No questions found for this subject.</div>";
    include 'layout/footer.php';
    exit();
}
?>

<div class="row g-4 mb-4" oncopy="return false" oncut="return false" onpaste="return false">
    <div class="col-12">
        <div class="card border-0 shadow-lg overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-8 bg-primary text-white p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-pen-fancy fa-2x text-white"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1"><?php echo $subject['sub_name']; ?></h3>
                                <p class="small mb-0 opacity-75">Anti-Cheat Mode Active | Right-click Disabled</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2 small">
                                    <i class="fas fa-file-alt me-2"></i><b><?php echo $total_questions; ?></b> Questions
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2 small">
                                    <i class="fas fa-clock me-2"></i><b><?php echo $exam_duration; ?></b> Minutes
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 bg-dark p-4 text-center">
                        <span class="small text-uppercase text-secondary">Time Remaining</span>
                        <div class="timer-display my-2">
                            <h2 id="timer" class="display-5 fw-bold text-warning font-monospace">00:00</h2>
                        </div>
                        <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                            <div id="timer-progress" class="progress-bar bg-warning" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cheat-alert" class="alert alert-warning border-0 shadow-lg d-none mb-4 animate__animated animate__shakeX">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
            <div>
                <h6 class="fw-bold mb-0">Security Warning!</h6>
                <p class="small mb-0">Focus lost. Please stay on this tab.</p>
            </div>
        </div>
        <span class="badge bg-danger rounded-pill px-3 py-2">Attempt <span id="cheat-count">0</span> / 3</span>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between small mb-1">
            <span>Overall Progress</span>
            <span id="answered-count">0/<?php echo $total_questions; ?></span>
        </div>
        <div class="progress" style="height: 5px;">
            <div id="progress-bar" class="progress-bar bg-success" style="width: 0%"></div>
        </div>
    </div>
</div>

<form id="examForm" action="submit_exam.php" method="POST">
    <input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white fw-bold border-0">Question Navigator</div>
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <?php 
                        for($n=1; $n<=$total_questions; $n++):
                        ?>
                        <div id="nav-q-<?php echo $n; ?>" class="badge bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width:35px; height:35px; cursor:pointer;"><?php echo $n; ?></div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php 
            $i = 1;
            while($q = $questions_query->fetch_assoc()):
            ?>
            <div class="card border-0 shadow-sm mb-4 question-card" id="q-box-<?php echo $i; ?>">
                <div class="card-header bg-white d-flex justify-content-between">
                    <span class="fw-bold">Question <?php echo $i; ?></span>
                    <span class="small text-muted">ID: #<?php echo $q['q_id']; ?></span>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-4 fw-bold"><?php echo htmlspecialchars($q['question']); ?></h5>
                    <div class="options-list">
                        <?php foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $key => $val): ?>
                        <div class="mb-3">
                            <input type="radio" class="btn-check" name="answer[<?php echo $q['q_id']; ?>]" id="q<?php echo $q['q_id'].$key; ?>" value="<?php echo $key; ?>" autocomplete="off" onchange="markAnswered(<?php echo $i; ?>)">
                            <label class="btn btn-outline-secondary w-100 text-start p-3" for="q<?php echo $q['q_id'].$key; ?>">
                                <b><?php echo $key; ?>.</b> <?php echo htmlspecialchars($q[$val]); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php $i++; endwhile; ?>
            
            <div class="text-center my-5">
                <button type="button" onclick="confirmSubmit()" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg">Submit Examination</button>
            </div>
        </div>
    </div>
</form>

<script>
// --- TIMER LOGIC ---
let durationInMinutes = <?php echo $exam_duration; ?>;
let timeLeft = durationInMinutes * 60;
const totalTime = timeLeft;

function updateTimer() {
    let mins = Math.floor(timeLeft / 60);
    let secs = timeLeft % 60;
    document.getElementById('timer').innerHTML = `${mins < 10 ? '0'+mins : mins}:${secs < 10 ? '0'+secs : secs}`;
    
    // Progress Bar
    let width = (timeLeft / totalTime) * 100;
    document.getElementById('timer-progress').style.width = width + '%';

    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        alert("Time is over! Submitting automatically.");
        document.getElementById('examForm').submit();
    }
    timeLeft--;
}
const timerInterval = setInterval(updateTimer, 1000);

// --- PROGRESS TRACKING ---
let answeredQuestions = new Set();
function markAnswered(qNum) {
    answeredQuestions.add(qNum);
    document.getElementById('nav-q-' + qNum).classList.remove('bg-light', 'text-dark');
    document.getElementById('nav-q-' + qNum).classList.add('bg-success', 'text-white');
    
    let total = <?php echo $total_questions; ?>;
    let count = answeredQuestions.size;
    document.getElementById('answered-count').innerHTML = count + '/' + total;
    document.getElementById('progress-bar').style.width = (count / total * 100) + '%';
}

// --- ANTI-CHEAT ---
let switchCount = 0;
document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        switchCount++;
        document.getElementById('cheat-alert').classList.remove('d-none');
        document.getElementById('cheat-count').innerText = switchCount;
        
        if (switchCount >= 3) {
            alert("Violation Limit Reached! Submitting Exam.");
            document.getElementById('examForm').submit();
        } else {
            alert("WARNING: Tab switching is tracked. Attempt " + switchCount + "/3");
        }
    }
});

function confirmSubmit() {
    if(confirm("Do you really want to submit the exam?")) {
        document.getElementById('examForm').submit();
    }
}

// Disable right click & copy
document.addEventListener('contextmenu', e => e.preventDefault());
document.onkeydown = (e) => {
    if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u' || e.key === 'i')) return false;
};
</script>

<style>
    .question-card { border-radius: 15px; border-top: 5px solid #0d6efd; }
    .btn-check:checked + .btn-outline-secondary { background-color: #e7f1ff; border-color: #0d6efd; color: #0d6efd; font-weight: bold; }
    .sticky-top { z-index: 100; }
</style>

<?php include 'layout/footer.php'; ?>