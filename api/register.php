<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $address = trim($_POST['address']);
    $role = $_POST['role'];
    $status = 0; // default not voted

    // Validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        die("Invalid name. Only letters and spaces allowed.");
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
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

    // Image upload
    $photo_name = $_FILES['Photo']['name'];
    $photo_tmp = $_FILES['Photo']['tmp_name'];
    $photo_path = "../uploads/" . $photo_name;
    move_uploaded_file($photo_tmp, $photo_path);

    // Insert user
    $stmt = $connect->prepare("INSERT INTO user (name, mobile, password, address, role, status, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $mobile, $password_hash, $address, $role, $status, $photo_name);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful. Please login.'); window.location='../index.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>