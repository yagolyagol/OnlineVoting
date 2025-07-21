<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['mobile'], $_POST['password'], $_POST['role'])) {
        $mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
        $password = trim($_POST['password']); 
        $role = mysqli_real_escape_string($connect, $_POST['role']);

       /* $query = "SELECT * FROM user WHERE mobile='$mobile' AND role='$role' LIMIT 1";
        $result = mysqli_query($connect, $query);*/
        $stmt = $connect->prepare("SELECT * FROM user WHERE mobile = ? AND role = ?");
        $stmt->bind_param("ss", $mobile, $role);
        $stmt->execute();
        $result = $stmt->get_result();
    }


 if ($result->num_rows > 0) {
    $userdata = $result->fetch_assoc();

        if (mysqli_num_rows($result) > 0) {
            $userdata = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Password verify (hashed)
            if (password_verify($password, $userdata['password'])) {
                $_SESSION['userdata'] = $userdata;

                // Fetch candidates
                $groupsQuery = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate'");
                $_SESSION['groupsdata'] = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);
                  echo "Login successful.Redirecting to dashboard...";

                header("Location: ../routes/dashboard.php");
                exit;
            } else{
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