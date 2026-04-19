<?php include 'layout/header.php'; ?>

<?php
// 1. Data fetch karein (ID pass karna zaroori hai database check ke liye)
$res_id = isset($_GET['res_id']) ? intval($_GET['res_id']) : 0;
$score = isset($_GET['score']) ? intval($_GET['score']) : 0;

// 2. Database se is result ka Subject ID aur us subject ke Total Questions fetch karein
$query = "SELECT r.sub_id, (SELECT COUNT(*) FROM questions WHERE sub_id = r.sub_id) as total_q 
          FROM results r WHERE r.res_id = $res_id";
$res_db = $conn->query($query);

if($res_db->num_rows > 0) {
    $row = $res_db->fetch_assoc();
    $total_questions = $row['total_q'];
} else {
    // Fallback agar res_id na mile (safety purpose)
    $total_questions = isset($_GET['total']) ? intval($_GET['total']) : 0;
}

// 3. Dynamic Calculation (100 Marks Base)
if ($total_questions > 0) {
    $percentage = ($score / $total_questions) * 100;
    $marks_per_que = 100 / $total_questions;
    $obtained_marks = $score * $marks_per_que;
} else {
    $percentage = 0;
    $obtained_marks = 0;
}

// Grade Determination
if($percentage >= 80) {
    $grade = 'A'; $grade_color = 'success'; $grade_message = 'Excellent Performance!';
} elseif($percentage >= 60) {
    $grade = 'B'; $grade_color = 'info'; $grade_message = 'Good Job!';
} elseif($percentage >= 40) {
    $grade = 'C'; $grade_color = 'warning'; $grade_message = 'Fair Attempt!';
} else {
    $grade = 'F'; $grade_color = 'danger'; $grade_message = 'Need Improvement';
}

$passed = $percentage >= 40;
?>

<style>
/* Dynamic Progress Circle Fix */
.progress-circle {
    width: 200px; height: 200px; border-radius: 50%;
    background: conic-gradient(
        <?php echo ($passed ? '#198754' : '#dc3545'); ?> <?php echo $percentage; ?>%, 
        #e9ecef 0deg
    );
    display: flex; align-items: center; justify-content: center; margin: 0 auto; position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.progress-circle::before {
    content: ''; position: absolute; width: 160px; height: 160px;
    border-radius: 50%; background: white;
}
.progress-circle span { position: relative; z-index: 1; font-size: 2.5rem; font-weight: 700; }

/* Rest of your existing animations... */
@keyframes slideInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes confetti { 0% { transform: translateY(0) rotate(0deg); opacity: 1; } 100% { transform: translateY(100vh) rotate(720deg); opacity: 0; } }
.result-card { animation: slideInUp 0.6s ease-out; }
.confetti { position: fixed; width: 10px; height: 10px; z-index: 1000; animation: confetti 4s linear forwards; }
</style>

<?php if($passed): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'];
    function createConfetti() {
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.top = '-10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            document.body.appendChild(confetti);
            setTimeout(() => confetti.remove(), 4000);
        }
    }
    createConfetti();
});
</script>
<?php endif; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg result-card overflow-hidden">
                <div class="card-header text-center py-4 border-0 <?php echo $passed ? 'bg-success' : 'bg-danger'; ?> text-white">
                    <h3 class="fw-bold mb-0">Exam Result</h3>
                    <small>Total Questions Checked: <?php echo $total_questions; ?></small>
                </div>

                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="progress-circle">
                            <span class="text-<?php echo $passed ? 'success' : 'danger'; ?>">
                                <?php echo round($percentage, 1); ?>%
                            </span>
                        </div>
                        <h4 class="fw-bold mt-4 text-<?php echo $grade_color; ?>"><?php echo $grade_message; ?></h4>
                        <p>Grade: <span class="badge bg-<?php echo $grade_color; ?>"><?php echo $grade; ?></span></p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6 text-center border-end">
                            <small class="text-muted d-block">Obtained Marks</small>
                            <h2 class="fw-bold text-primary mb-0"><?php echo round($obtained_marks, 1); ?></h2>
                        </div>
                        <div class="col-6 text-center">
                            <small class="text-muted d-block">Total Marks</small>
                            <h2 class="fw-bold text-secondary mb-0">100</h2>
                        </div>
                    </div>

                    <div class="alert bg-light border-0 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check-circle text-success me-1"></i> Correct: <b><?php echo $score; ?></b></span>
                            <span><i class="fas fa-times-circle text-danger me-1"></i> Wrong: <b><?php echo ($total_questions - $score); ?></b></span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: <?php echo $percentage; ?>%"></div>
                            <div class="progress-bar bg-danger" style="width: <?php echo (100 - $percentage); ?>%"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="print_result.php?res_id=<?php echo $res_id; ?>" class="btn btn-primary btn-lg rounded-pill shadow">
                            <i class="fas fa-download me-2"></i>Download Certificate
                        </a>
                        <a href="student_dashboard.php" class="btn btn-outline-secondary rounded-pill">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>