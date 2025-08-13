<?php
session_start();
include '../api/connect.php';

// Only allow admin
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $connect->query("UPDATE user SET status=1 WHERE id=$id"); // approve
}

// Redirect back to dashboard
header("Location: ../routes/admin_dashboard.php");
exit;
?>
