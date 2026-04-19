<?php
session_start();
require_once 'config/config.php';

if(isset($_POST['login'])) {
    $email_user = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // 1. FIRST CHECK: Is this an Admin?
    $admin_check = $conn->query("SELECT * FROM admin WHERE username='$email_user'");
    
    if($admin_check->num_rows > 0) {
        $admin = $admin_check->fetch_assoc();
        if(password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['role'] = 'admin';
            header("Location: admin/dashboard.php");
            exit();
        }
    }

    // 2. SECOND CHECK: Is this a Student?
    $student_check = $conn->query("SELECT * FROM users WHERE email='$email_user'");
    
    if($student_check->num_rows > 0) {
        $student = $student_check->fetch_assoc();
        if(password_verify($password, $student['password'])) {
            $_SESSION['user_id'] = $student['user_id'];
            $_SESSION['user_name'] = $student['full_name'];
            $_SESSION['role'] = 'student';
            header("Location: student_dashboard.php");
            exit();
        }
    }

    // If both checks fail
    error_log("[" . date('Y-m-d H:i:s') . "] Failed universal login: $email_user" . PHP_EOL, 3, "error_log.txt");
    $error = "Invalid Credentials! Please check your username/email and password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | ExamFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .login-header { background: #0d6efd; color: white; border-radius: 15px 15px 0 0; padding: 30px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="login-header text-center">
                    <h3 class="fw-bold mb-0">Portal Login</h3>
                    <p class="small opacity-75 mb-0">Admin & Student Gateway</p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger small py-2"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username or Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Enter your ID" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold">Sign In</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">New Student? <a href="register.php" class="text-decoration-none">Create Account</a></p>
                        <a href="index.php" class="small text-decoration-none"><i class="fas fa-arrow-left mt-3"></i> Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>