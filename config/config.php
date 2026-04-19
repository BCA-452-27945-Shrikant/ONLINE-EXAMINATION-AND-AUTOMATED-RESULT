<?php
date_default_timezone_set('Asia/Kolkata'); // Fixes the 5:30 hour delay
// ... your existing db connection code ...
// 1. Database Credentials
$host = "localhost";
$user = "u145349340_exam";
$pass = "@Shrikant7633";
$dbname = "u145349340_exam";

// 2. Error Logging Logic
ini_set('display_errors', 0); // Hide errors from the user/student
ini_set('log_errors', 1);     // Enable error logging
ini_set('error_log', __DIR__ . '/../error_log.txt'); // Save to our .txt file

// 3. Connect to Database
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection Failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Manually log custom errors
    error_log("[" . date('Y-m-d H:i:s') . "] Custom Error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
    die("A system error occurred. Please check the error_log.txt file.");
}
?>