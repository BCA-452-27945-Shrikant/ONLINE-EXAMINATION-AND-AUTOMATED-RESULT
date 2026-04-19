<?php
session_start();
require_once 'config/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sub_id = intval($_POST['sub_id']);
    $answers = isset($_POST['answer']) ? $_POST['answer'] : []; 
    
    $score = 0;

    // Loop through answers and calculate score
    foreach($answers as $q_id => $user_choice) {
        $q_id = intval($q_id);
        $res = $conn->query("SELECT ans_correct FROM questions WHERE q_id = $q_id");
        if($res && $q_data = $res->fetch_assoc()) {
            if($q_data['ans_correct'] == $user_choice) {
                $score++;
            }
        }
    }

    // Attempt to Save Result
    $sql = "INSERT INTO results (user_id, sub_id, total_score) VALUES ('$user_id', '$sub_id', '$score')";
    
    if($conn->query($sql)) {
        // Redirect to result page with the score
        header("Location: result.php?score=$score&total=".count($answers));
        exit();
    } else {
        // This will now write the EXACT SQL error to your .txt file
        error_log("[" . date('Y-m-d H:i:s') . "] Submission Query Failed: " . $conn->error . " | SQL: $sql" . PHP_EOL, 3, "error_log.txt");
        die("Database Error: Check your error_log.txt file for the specific reason.");
    }
}
?>