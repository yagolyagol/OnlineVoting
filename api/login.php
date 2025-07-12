<?php
session_start();
include("connect.php");

$mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
$password = $_POST['password'];
$role = mysqli_real_escape_string($connect, $_POST['role']);

// Fetch user by mobile and role
$query = "SELECT * FROM user WHERE mobile='$mobile' AND role='$role'";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
    $userdata = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // If password is plain text
    if ($userdata['password'] === $password) {

        // Store session
        $_SESSION['userdata'] = $userdata;

        // Fetch all candidates
        $groupsQuery = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate'");
        $_SESSION['groupsdata'] = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);

        // Redirect to dashboard
        header("Location: ../routes/dashboard.php");
        exit;

    } else {
        echo "<script>alert('Incorrect password'); window.location='../index.html';</script>";
    }
} else {
    echo "<script>alert('User not found'); window.location='../index.html';</script>";
}
?>




 