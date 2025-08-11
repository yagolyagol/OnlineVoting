<?php
session_start();
include("connect.php");

if (!isset($_SESSION['userdata'])) {
    header("Location: ../login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $userId = $_SESSION['userdata']['id'];

    // Fetch user from DB
    $stmt = $connect->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        echo "<script>alert('Current password is incorrect'); window.location='../change_password.html';</script>";
        exit;
    }

    // ✅ Enforce new password rule
    if (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $newPassword)) {
        echo "<script>alert('New password must be at least 8 characters and contain at least one number and one special character'); window.location='../change_password.html';</script>";
        exit;
    }

    // ✅ Confirm match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New password and confirmation do not match'); window.location='../change_password.html';</script>";
        exit;
    }

    // ✅ Hash and update
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $connect->prepare("UPDATE user SET password = ? WHERE id = ?");
    $updateStmt->bind_param("si", $hashedPassword, $userId);

    if ($updateStmt->execute()) {
        echo "<script>alert('Password updated successfully!'); window.location='../routes/voter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating password'); window.location='../change_password.html';</script>";
    }
}
?>
