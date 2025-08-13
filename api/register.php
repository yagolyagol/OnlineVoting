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
    $status    = 0; // default status

    // Validations
    if (!$email) die("Invalid email address.");
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) die("Invalid name. Only letters and spaces allowed.");
    if (!preg_match("/^\d{10}$/", $mobile)) die("Mobile must be exactly 10 digits.");
    if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*]).{6,}$/', $password))
        die("Password must contain at least one number, one special character, and be at least 6 characters long.");
    if ($password !== $cpassword) die("Passwords do not match.");

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

    // Insert into main 'user' table
    $stmt = $connect->prepare("INSERT INTO user 
        (name, mobile, email, password, dob, gender, address, voter_id, role, status, profile_image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssis", 
        $name, $mobile, $email, $password_hash, $dob, $gender, $address, 
        $voter_id, $role, $status, $photo_filename);

    if (!$stmt->execute()) {
        die("Error inserting into user table: " . $stmt->error);
    }
    // Get the inserted user_id

    $user_id = $stmt->insert_id;
    $stmt->close();


    // ✅ Insert into voters table if role is voter
if ($role === 'voter') {
    $stmtVoter = $connect->prepare("INSERT INTO voters (user_id, voter_id, voted) VALUES (?, ?, 0)");
    $stmtVoter->bind_param("is", $user_id, $voter_id); // i = integer, s = string
    if (!$stmtVoter->execute()) {
        die("Error inserting into voters table: " . $stmtVoter->error);
    }
    $stmtVoter->close();
}



    // If role is candidate, insert into 'candidate' table linked by user_id
    if ($role === 'candidate') {
        $votes = 0; // default votes
        $stmt2 = $connect->prepare("INSERT INTO candidate
            (user_id, name, mobile, address, profile_image, votes, role, password, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt2->bind_param("issssissi", 
            $user_id, $name, $mobile, $address, $photo_filename, $votes, $role, $password_hash, $status);

        if (!$stmt2->execute()) {
            die("Error inserting into candidate table: " . $stmt2->error);
        }
        $stmt2->close();
    }

    echo "<script>alert('Registration successful! Please login.'); window.location='../login.html';</script>";
} else {
    echo "Invalid request method.";
}
?>
