<?php
require_once 'config/config.php';

// --- CHANGE THESE TO YOUR PREFERRED CREDENTIALS ---
$new_user = 'Shri'; 
$new_pass = 'Shrikant1234'; 
// --------------------------------------------------

// Securely hash the password
$hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);

// Clear the old admin table to avoid duplicates
$conn->query("TRUNCATE TABLE admin");

// Insert your new credentials
$sql = "INSERT INTO admin (username, password) VALUES ('$new_user', '$hashed_pass')";

if ($conn->query($sql)) {
    echo "<div style='padding:20px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; font-family:sans-serif;'>";
    echo "<h3>Success! Admin Reset Complete.</h3>";
    echo "<p><strong>Username:</strong> $new_user</p>";
    echo "<p><strong>Password:</strong> $new_pass</p>";
    echo "<hr><p>Please <strong>DELETE</strong> this file (reset_admin.php) immediately for security.</p>";
    echo "<a href='admin/index.php'>Go to Login Page</a>";
    echo "</div>";
} else {
    // Log error to your .txt file if something goes wrong
    error_log("[" . date('Y-m-d H:i:s') . "] Reset Script Error: " . $conn->error . PHP_EOL, 3, "error_log.txt");
    echo "Error occurred. Check your error_log.txt file.";
}
?>