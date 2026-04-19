<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit(); }
require_once '../config/config.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM questions WHERE q_id = $id";
    
    if($conn->query($sql)) {
        // Redirect back with a success message in the URL
        header("Location: manage_questions.php?msg=deleted");
    } else {
        header("Location: manage_questions.php?msg=error");
    }
} else {
    header("Location: manage_questions.php");
}
?>