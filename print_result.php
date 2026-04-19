<?php
session_start();
require_once 'config/config.php';
$res_id = $_GET['res_id'];
$data = $conn->query("SELECT r.*, s.sub_name, u.full_name FROM results r 
                      JOIN subjects s ON r.sub_id = s.sub_id 
                      JOIN users u ON r.user_id = u.user_id 
                      WHERE r.res_id = $res_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Result_<?php echo $data['full_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
        .certificate-box { border: 10px solid #eee; padding: 50px; margin-top: 50px; }
    </style>
</head>
<body onload="window.print()">
    <div class="container certificate-box text-center">
        <h1 class="display-4">EXAMFLOW REPORT</h1>
        <p class="lead">Official Examination Certificate</p>
        <hr>
        <div class="my-5">
            <h3>This is to certify that <strong><?php echo $data['full_name']; ?></strong></h3>
            <p>has completed the examination for</p>
            <h2 class="text-primary"><?php echo $data['sub_name']; ?></h2>
        </div>
        <div class="row mt-5">
            <div class="col-6"><h5>Score: <?php echo $data['total_score']; ?></h5></div>
            <div class="col-6"><h5>Date: <?php echo date('d-m-Y', strtotime($data['date_time'])); ?></h5></div>
        </div>
        <div class="mt-5 no-print">
            <button class="btn btn-primary" onclick="window.print()">Download PDF</button>
        </div>
    </div>
</body>
</html>