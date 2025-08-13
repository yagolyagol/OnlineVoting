<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Invalid request method'); window.location='../login.html';</script>";
    exit;
}

// Validate inputs
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';

if (empty($email) || empty($password) || empty($role)) {
    echo "<script>alert('Please fill all fields'); window.location='../login.html';</script>";
    exit;
}

// Prepare SQL
$stmt = $connect->prepare("SELECT * FROM user WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found'); window.location='../login.html';</script>";
    exit;
}

$userdata = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $userdata['password'])) {
    echo "<script>alert('Incorrect password'); window.location='../login.html';</script>";
    exit;
}

// ✅ Password correct, regenerate session ID for security
session_regenerate_id(true);
$_SESSION['userdata'] = $userdata;

// Load extra data for voters
if ($role === 'voter') {
    $groupsQuery = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate'");
    $_SESSION['groupsdata'] = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);
}

// Redirect based on role
switch ($role) {
    case 'voter':
        header("Location: ../routes/voter_dashboard.php");
        break;
    case 'candidate':
        header("Location: ../routes/candidate_dashboard.php");
        break;
    case 'admin':
        header("Location: ../routes/admin_dashboard.php");
        break;
    default:
        // Just in case
        echo "<script>alert('Unknown role'); window.location='../login.html';</script>";
        break;
}

exit;
?>












<?php

/*ini_set('display_errors', 1);
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

            // ✅ Only verify hash — no special char requirement here
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
}*/
?>
