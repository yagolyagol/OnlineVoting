<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = trim($_POST['role']);

        // Prepare SQL to check email + role
        $stmt = $connect->prepare("SELECT * FROM user WHERE email = ? AND role = ?");
        $stmt->bind_param("ss", $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userdata = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $userdata['password'])) {
                $_SESSION['userdata'] = $userdata;

                // Load extra candidate list for voters
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
        echo "<script>alert('Please fill all fields'); window.location='../login.html';</script>";
    }

} else {
    echo "<script>alert('Invalid request method'); window.location='../login.html';</script>";
}
?>


login(existing users)
// Login
$enteredPassword = $_POST['password'];

// Fetch hashed password from DB for this user
$storedHash = $row['password']; 

if (password_verify($enteredPassword, $storedHash)) {
    // Password matches, log in
} else {
    die("Invalid credentials.");
}

password change (exixting users)
// When a user changes password
$newPassword = $_POST['new_password'];

if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*]).{6,}$/', $newPassword)) {
    die("New password must contain at least one number, one special character, and be at least 6 characters long.");
}

$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
// Save $hashedNewPassword to DB
