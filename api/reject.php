<?php
session_start();
include '../api/connect.php';

if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Set status to -1 to indicate "Rejected"
    $query = $connect->query("UPDATE user SET status = -1 WHERE id = $id AND role = 'candidate'");

    if (!$query) {
        echo "<script>alert('Failed to reject candidate.'); window.location='../routes/admin_dashboard.php';</script>";
        exit;
    }
}

// Redirect back
header("Location: ../routes/admin_dashboard.php");
exit;
?>