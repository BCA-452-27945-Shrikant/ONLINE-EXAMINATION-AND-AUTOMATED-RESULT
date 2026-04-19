<?php include 'layout/header.php'; ?>

<?php
$u_id = $_SESSION['user_id'];

// Handling Profile Update Logic
if(isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $f_name = $conn->real_escape_string($_POST['f_name']);
    $m_name = $conn->real_escape_string($_POST['m_name']);
    $dob = $_POST['dob'];
    
    $pic_query = "";
    if(!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "assets/img/profiles/";
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_ext = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $file_name = "user_" . $u_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $pic_query = ", profile_pic = '$file_name'";
        }
    }

    $sql = "UPDATE users SET full_name='$name', father_name='$f_name', mother_name='$m_name', dob='$dob' $pic_query WHERE user_id=$u_id";
    
    if($conn->query($sql)) {
        $_SESSION['user_name'] = $name; // Sync session name
        echo "<div class='alert alert-success alert-dismissible fade show shadow-sm border-0' role='alert'>
                <i class='fas fa-check-circle me-2'></i>Profile updated successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        error_log("[" . date('Y-m-d H:i:s') . "] Profile Update Error: " . $conn->error . PHP_EOL, 3, "error_log.txt");
        echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0' role='alert'>
                <i class='fas fa-exclamation-triangle me-2'></i>Update failed. Please try again.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
}

// Fetch current user data
$user = $conn->query("SELECT * FROM users WHERE user_id = $u_id")->fetch_assoc();
$profile_img = (!empty($user['profile_pic']) && file_exists("assets/img/profiles/" . $user['profile_pic'])) 
               ? "assets/img/profiles/" . $user['profile_pic'] : "assets/img/default.png";

// Calculate age from DOB if available
$age = '';
if(!empty($user['dob'])) {
    $birthDate = new DateTime($user['dob']);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
}
?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-user-cog fa-2x text-primary"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h3 class="fw-bold mb-1">Profile Settings</h3>
                <p class="text-muted mb-0">Manage your personal information and profile picture</p>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Main Profile Card -->
        <div class="card border-0 shadow-lg overflow-hidden">
            <!-- Card Header with Gradient -->
            <div class="card-header bg-gradient-primary border-0 py-4">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-id-card fa-2x me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Personal Profile Management</h5>
                        <p class="small mb-0 opacity-75">Update your details and keep your profile up to date</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <form method="POST" enctype="multipart/form-data">
                    <!-- Profile Content -->
                    <div class="row g-0">
                        <!-- Left Column - Profile Photo -->
                        <div class="col-lg-4 border-end p-4 text-center bg-light">
                            <!-- Profile Image Preview -->
                            <div class="position-relative d-inline-block mb-4">
                                <div class="profile-image-wrapper">
                                    <img src="<?php echo $profile_img; ?>" 
                                         id="profilePreview"
                                         class="rounded-circle border border-4 border-white shadow" 
                                         style="width: 180px; height: 180px; object-fit: cover;"
                                         alt="Profile">
                                    <div class="profile-image-overlay" onclick="document.getElementById('profile_pic_input').click();">
                                        <i class="fas fa-camera"></i>
                                        <span>Change Photo</span>
                                    </div>
                                </div>
                                
                                <!-- Online Status Indicator -->
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-3 border-white"></span>
                            </div>

                            <!-- File Upload Section -->
                            <div class="upload-area p-3 rounded-3 bg-white mb-3">
                                <input type="file" name="profile_pic" id="profile_pic_input" class="d-none" accept="image/*" onchange="previewImage(this);">
                                
                                <button type="button" class="btn btn-outline-primary w-100 rounded-pill mb-2" onclick="document.getElementById('profile_pic_input').click();">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>Choose New Photo
                                </button>
                                
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    JPG, PNG (Max 2MB)
                                </p>
                            </div>

                            <!-- Profile Summary Card -->
                            <div class="card border-0 bg-white mt-3">
                                <div class="card-body text-start p-3">
                                    <h6 class="fw-bold small text-uppercase text-muted mb-3">
                                        <i class="fas fa-chart-pie me-2"></i>Profile Summary
                                    </h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted">Member Since</span>
                                        <span class="small fw-bold"><?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted">Age</span>
                                        <span class="small fw-bold"><?php echo $age ? $age . ' years' : 'Not set'; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted">Profile Status</span>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">
                                            <i class="fas fa-check-circle me-1 fa-xs"></i>Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Form Fields -->
                        <div class="col-lg-8 p-4">
                            <!-- Full Name -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">
                                    <i class="fas fa-user text-primary me-2"></i>Full Name
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-user-circle text-primary"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0" 
                                           value="<?php echo $user['full_name']; ?>" 
                                           placeholder="Enter your full name" required>
                                </div>
                            </div>

                            <!-- Parent Details -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">
                                        <i class="fas fa-male text-primary me-2"></i>Father's Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-user-tie text-primary"></i>
                                        </span>
                                        <input type="text" name="f_name" class="form-control" 
                                               value="<?php echo $user['father_name']; ?>" 
                                               placeholder="Enter father's name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">
                                        <i class="fas fa-female text-primary me-2"></i>Mother's Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-user-tie text-primary"></i>
                                        </span>
                                        <input type="text" name="m_name" class="form-control" 
                                               value="<?php echo $user['mother_name']; ?>" 
                                               placeholder="Enter mother's name">
                                    </div>
                                </div>
                            </div>

                            <!-- DOB and Email -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>Date of Birth
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-birthday-cake text-primary"></i>
                                        </span>
                                        <input type="date" name="dob" class="form-control" 
                                               value="<?php echo $user['dob']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">
                                        <i class="fas fa-envelope text-primary me-2"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-at text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?php echo $user['email']; ?>" 
                                               readonly disabled>
                                    </div>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                            </div>

                            <!-- Additional Info (Optional) -->
                            <div class="bg-light p-3 rounded-3 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications" checked>
                                    <label class="form-check-label small" for="notifications">
                                        Receive email notifications about exam results and updates
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" name="update_profile" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <a href="student_dashboard.php" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Settings Card (Optional) -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-shield-alt text-primary me-2"></i>Account Security
                        </h6>
                        <p class="small text-muted mb-0">Manage your password and security settings</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="change_password.php" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-key me-2"></i>Change Password
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

.profile-image-wrapper {
    position: relative;
    cursor: pointer;
}

.profile-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(13, 110, 253, 0.8);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
    cursor: pointer;
}

.profile-image-wrapper:hover .profile-image-overlay {
    opacity: 1;
}

.profile-image-overlay i {
    font-size: 2rem;
    margin-bottom: 5px;
}

.profile-image-overlay span {
    font-size: 0.75rem;
    font-weight: 500;
}

.upload-area {
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.02) !important;
}

.input-group-text {
    border-right: none;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
    
    .profile-image-wrapper img {
        width: 140px !important;
        height: 140px !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 button,
    .d-flex.gap-3 a {
        width: 100%;
    }
}
</style>

<!-- JavaScript for Image Preview -->
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
            
            // Show success toast
            showToast('Image selected successfully!', 'success');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `position-fixed bottom-0 end-0 m-3 p-3 bg-${type} text-white rounded-3 shadow-lg`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Validate file size before upload
document.getElementById('profile_pic_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // in MB
        if (fileSize > 2) {
            alert('File size must be less than 2MB!');
            this.value = ''; // Clear the input
        }
    }
});
</script>

<?php include 'layout/footer.php'; ?>