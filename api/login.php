<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['mobile']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        $mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
        $password = trim($_POST['password']);
        $role = mysqli_real_escape_string($connect, $_POST['role']);

        $stmt = $connect->prepare("SELECT * FROM user WHERE mobile = ? AND role = ?");
        $stmt->bind_param("ss", $mobile, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userdata = $result->fetch_assoc();

            if (password_verify($password, $userdata['password'])) {
                $_SESSION['userdata'] = $userdata;

                // For voters, also get candidate data
               if ($role === 'voter') {
    $groupsQuery = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate'");
    $_SESSION['groupsdata'] = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);
    header("Location: ../routes/voter_dashboard.php");
} elseif ($role === 'candidate') {
    header("Location: ../routes/candidate_dashboard.php");
} elseif ($role === 'admin') {
    header("Location: ../routes/admin_dashboard.php");
}

                exit;
            } else {
                echo "<script>alert('Incorrect password'); window.location='../login.html';</script>";
            }
        } else {
            echo "<script>alert('User not found'); window.location='../login.html';</script>";
        }

    } else {
        echo "<script>alert('Please fill all fields'); window.location='login.html';</script>";
    }

} else {
    echo "<script>alert('Invalid request method'); window.location='login.html';</script>";
}
?>
