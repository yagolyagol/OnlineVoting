<?php
include("../api/connect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$user_id = intval($_POST['id']); // user table id
$name = htmlspecialchars(trim($_POST['name']));
$address = htmlspecialchars(trim($_POST['address']));
$candidate_bio = htmlspecialchars(trim($_POST['candidate_bio'] ?? ''));

// Handle profile image upload
$photo_filename = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_image']['tmp_name'];
    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg','jpeg','png','gif'];
    if (in_array(strtolower($ext), $allowed)) {
        $photo_filename = uniqid("profile_") . "." . $ext;
        move_uploaded_file($tmp_name, "../uploads/" . $photo_filename);
    }
}

// 1️⃣ Update user table
if ($photo_filename) {
    $stmt = $connect->prepare("UPDATE user SET name=?, address=?, profile_image=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $address, $photo_filename, $user_id);
} else {
    $stmt = $connect->prepare("UPDATE user SET name=?, address=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $address, $user_id);
}
$stmt->execute();
$stmt->close();

// 2️⃣ Update candidate table using user_id
if ($photo_filename) {
    $stmt2 = $connect->prepare("UPDATE candidate SET name=?, address=?, profile_image=?, candidate_bio=? WHERE user_id=?");
    $stmt2->bind_param("ssssi", $name, $address, $photo_filename, $candidate_bio, $user_id);
} else {
    $stmt2 = $connect->prepare("UPDATE candidate SET name=?, address=?, candidate_bio=? WHERE user_id=?");
    $stmt2->bind_param("sssi", $name, $address, $candidate_bio, $user_id);
}
$stmt2->execute();
$stmt2->close();

header("Location: ../routes/candidate_dashboard.php");
exit;
?>
