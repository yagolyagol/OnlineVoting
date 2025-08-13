<?php
session_start();
include '../api/connect.php';

if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $connect->query("UPDATE user SET status=0 WHERE id=$id"); // reject
}

header("Location: ../routes/admin_dashboard.php");
exit;
?>
