<?php
session_start();
include("connect.php");

   

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $name      = htmlspecialchars(trim($_POST['name']));
    $mobile    = htmlspecialchars(trim($_POST['mobile']));
    $email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $dob       = $_POST['dob'];
    $gender    = $_POST['gender'];
    $address   = htmlspecialchars(trim($_POST['address']));
    $voter_id  = htmlspecialchars(trim($_POST['voter_id']));
    $role      = $_POST['role'];
    $status    = 0; // Default (e.g., not voted yet)

    // Server-side validations
    if (!$email) {
        die("Invalid email address.");
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        die("Invalid name. Only letters and spaces allowed.");
    }

    if (!preg_match("/^\d{10}$/", $mobile)) {
        die("Mobile must be exactly 10 digits.");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters.");
    }

    if ($password !== $cpassword) {
        die("Passwords do not match.");
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Profile picture upload
    $photo_filename = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['photo']['tmp_name'];
        $original_name = basename($_FILES['photo']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($ext), $allowed)) {
            $photo_filename = uniqid("profile_") . "." . $ext;
            $upload_path = "../uploads/" . $photo_filename;
            move_uploaded_file($tmp_name, $upload_path);
        } else {
            die("Invalid image format. Only JPG, PNG, or GIF allowed.");
        }
    } else {
        die("Please upload a profile picture.");
    }

    // Prepare SQL insert
    $stmt = $connect->prepare("INSERT INTO user 
        (name, mobile, email, password, dob, gender, address, voter_id, role, status, profile_image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssis", 
        $name, $mobile, $email, $password_hash, $dob, $gender, $address, 
        $voter_id, $role, $status, $photo_filename);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location='../login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}
?>
