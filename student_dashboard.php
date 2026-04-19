<?php include 'layout/header.php'; ?>

<?php 
// Fetch Fresh User Data for Sidebar
$u_id = $_SESSION['user_id'];
$user_data = $conn->query("SELECT * FROM users WHERE user_id = $u_id")->fetch_assoc();

// Logic for Profile Image
$img_path = "assets/img/default.png"; 
if(!empty($user_data['profile_pic']) && file_exists("assets/img/profiles/" . $user_data['profile_pic'])) {
    $img_path = "assets/img/profiles/" . $user_data['profile_pic'];
}

// Stats Logic
$total_done = $conn->query("SELECT res_id FROM results WHERE user_id = $u_id")->num_rows;
?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient-primary overflow-hidden">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="text-white fw-bold mb-2">Welcome back, <?php echo explode(' ', $user_data['full_name'])[0]; ?>! 👋</h3>
                        <p class="text-white opacity-75 mb-0">Track your progress and continue your learning journey</p>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- Decorative Elements -->
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-circle fa-10x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column - Profile Card -->
    <div class="col-lg-3">
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center p-4">
                <div class="position-relative d-inline-block mb-3">
                    <img src="<?php echo $img_path; ?>" 
                         class="rounded-circle border border-3 border-primary shadow" 
                         style="width: 120px; height: 120px; object-fit: cover;" 
                         alt="Profile">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-2 border-white"></span>
                </div>
                <h5 class="fw-bold mb-1"><?php echo $user_data['full_name']; ?></h5>
                <p class="text-muted small mb-3">
                    <i class="fas fa-id-card me-1 text-primary"></i>
                    ID: <?php echo str_pad($u_id, 6, '0', STR_PAD_LEFT); ?>
                </p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-graduation-cap me-1"></i>Student
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i>Active
                    </span>
                </div>
                
                <hr class="my-3">
                
                <div class="d-grid gap-2">
                    <a href="profile.php" class="btn btn-primary rounded-pill py-2">
                        <i class="fas fa-user-edit me-2"></i> Edit Profile
                    </a>
                    <a href="exam_history.php" class="btn btn-outline-primary rounded-pill py-2">
                        <i class="fas fa-history me-2"></i> Exam History
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="card border-0 shadow-sm mb-4 bg-dark text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="small text-uppercase text-secondary">Exams Completed</span>
                    <div class="bg-primary bg-opacity-25 rounded-circle p-2">
                        <i class="fas fa-check-circle text-primary"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-0"><?php echo $total_done; ?></h2>
                <p class="small text-secondary mt-2 mb-0">
                    <i class="fas fa-arrow-up text-success me-1"></i> Keep going!
                </p>
            </div>
        </div>

        <!-- Quick Tips Card -->
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-lightbulb text-primary me-2"></i>Quick Tips
                </h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2 fa-sm"></i> Read questions carefully</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2 fa-sm"></i> Manage your time wisely</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2 fa-sm"></i> Review before submitting</li>
                    <li><i class="fas fa-check-circle text-success me-2 fa-sm"></i> Stay calm and focused</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column - Exams List -->
    <div class="col-lg-9">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Available Examinations</h4>
                <p class="text-muted small mb-0">Select an exam to begin your assessment</p>
            </div>
            <span class="badge bg-white text-dark shadow-sm border py-2 px-3 rounded-pill">
                <i class="fas fa-clock me-2 text-primary"></i>
                <?php echo date('d M Y | h:i A'); ?>
            </span>
        </div>

        <!-- Exams Grid -->
        <div class="row g-4">
            <?php 
            $now = date('Y-m-d H:i:s');
            $res = $conn->query("SELECT * FROM subjects ORDER BY start_time ASC");

            if($res->num_rows > 0):
                while($sub = $res->fetch_assoc()): 
                    $sub_id = $sub['sub_id'];
                    $check = $conn->query("SELECT res_id FROM results WHERE user_id = $u_id AND sub_id = $sub_id");
                    $is_done = ($check->num_rows > 0);
                    
                    $is_started = ($now >= $sub['start_time']);
                    $is_ended = ($now > $sub['end_time']);

                    // Status configuration
                    if($is_done) {
                        $status_color = "success";
                        $status_icon = "check-circle";
                        $status_text = "Completed";
                    } elseif($is_ended) {
                        $status_color = "danger";
                        $status_icon = "times-circle";
                        $status_text = "Expired";
                    } elseif(!$is_started) {
                        $status_color = "warning";
                        $status_icon = "hourglass-half";
                        $status_text = "Upcoming";
                    } else {
                        $status_color = "primary";
                        $status_icon = "play-circle";
                        $status_text = "Available";
                    }
            ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 exam-card">
                    <div class="card-body p-4">
                        <!-- Header with Status -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-<?php echo $status_color; ?> bg-opacity-10 text-<?php echo $status_color; ?> px-3 py-2 rounded-pill mb-2">
                                    <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                                    <?php echo $status_text; ?>
                                </span>
                                <h5 class="fw-bold mb-1"><?php echo $sub['sub_name']; ?></h5>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-primary opacity-25"></i>
                        </div>
                        
                        <!-- Exam Details -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="small text-muted">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                <?php echo date('d M Y', strtotime($sub['start_time'])); ?>
                            </span>
                            <span class="small text-muted">
                                <i class="fas fa-clock me-1 text-primary"></i>
                                <?php echo date('h:i A', strtotime($sub['start_time'])); ?>
                            </span>
                        </div>

                        <!-- Time Info -->
                        <?php if(!$is_done && !$is_ended): ?>
                        <div class="mb-3">
                            <?php if(!$is_started): 
                                $diff = strtotime($sub['start_time']) - strtotime($now);
                                $hours = floor($diff / 3600);
                                $minutes = floor(($diff % 3600) / 60);
                            ?>
                                <div class="small bg-light p-2 rounded-3">
                                    <i class="fas fa-hourglass-start text-warning me-2"></i>
                                    <span class="fw-bold">Starts in:</span> <?php echo $hours; ?>h <?php echo $minutes; ?>m
                                </div>
                            <?php elseif($is_started && !$is_ended):
                                $diff = strtotime($sub['end_time']) - strtotime($now);
                                $hours = floor($diff / 3600);
                                $minutes = floor(($diff % 3600) / 60);
                            ?>
                                <div class="small bg-light p-2 rounded-3">
                                    <i class="fas fa-hourglass-end text-primary me-2"></i>
                                    <span class="fw-bold">Time left:</span> <?php echo $hours; ?>h <?php echo $minutes; ?>m
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Action Button -->
                        <?php if($is_done): ?>
                            <a href="view_result.php?id=<?php echo $sub_id; ?>" class="btn btn-outline-success w-100 rounded-pill py-2">
                                <i class="fas fa-chart-bar me-2"></i> View Result
                            </a>
                        <?php elseif($is_ended): ?>
                            <button class="btn btn-outline-secondary w-100 rounded-pill py-2" disabled>
                                <i class="fas fa-ban me-2"></i> Exam Expired
                            </button>
                        <?php elseif(!$is_started): ?>
                            <button class="btn btn-outline-warning w-100 rounded-pill py-2" disabled>
                                <i class="fas fa-clock me-2"></i> Coming Soon
                            </button>
                        <?php else: ?>
                            <a href="take_exam.php?id=<?php echo $sub_id; ?>" class="btn btn-primary w-100 rounded-pill py-3">
                                <i class="fas fa-play-circle me-2"></i> Start Exam
                                <i class="fas fa-arrow-right ms-2 fa-sm"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-4x text-muted opacity-25"></i>
                            </div>
                            <h5 class="fw-bold mb-2">No Examinations Scheduled</h5>
                            <p class="text-muted small mb-0">Check back later for upcoming exams</p>
                        </div>
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
    position: relative;
}
.bg-opacity-20 {
    --bs-bg-opacity: 0.2;
}
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
.exam-card {
    transition: all 0.3s ease;
}
.exam-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(13, 110, 253, 0.15) !important;
}
.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7, #0a58ca);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}
.btn-outline-primary {
    border-color: #0d6efd;
    color: #0d6efd;
}
.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border-color: transparent;
}
.fa-10x {
    font-size: 10rem;
}
.rounded-3 {
    border-radius: 0.5rem;
}
</style>

<?php include 'layout/footer.php'; ?>