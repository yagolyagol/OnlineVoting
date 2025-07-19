<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['mobile'], $_POST['password'], $_POST['role'])) {
        $mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
        $password = $_POST['password'];
        $role = mysqli_real_escape_string($connect, $_POST['role']);

        $query = "SELECT * FROM user WHERE mobile='$mobile' AND role='$role'";
        $result = mysqli_query($connect, $query);

        if (mysqli_num_rows($result) > 0) {
            $userdata = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Password verify (hashed)
            if (password_verify($password, $userdata['password'])) {
                $_SESSION['userdata'] = $userdata;

                // Fetch candidates
                $groupsQuery = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate'");
                $_SESSION['groupsdata'] = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);

                header("Location: ../routes/dashboard.php");
                exit;
            } else {
                echo "<script>alert('Incorrect password'); window.location='../login.html';</script>";
            }
        } else {
            echo "<script>alert('User not found'); window.location='../login.html';</script>";
        }

    } else {
        echo "<script>alert('Please fill all fields'); window.location='../login.html';</script>";
    }

} else {
    echo "<script>alert('Invalid request method'); window.location='../login.html';</script>";
}
?>