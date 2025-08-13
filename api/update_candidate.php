<?php
include("../api/connect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$id = $_POST['id'];
$candidate_id = $_POST['candidate_id'];
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

// Update user table
if ($photo_filename) {
    $stmt = $connect->prepare("UPDATE user SET name=?, address=?, profile_image=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $address, $photo_filename, $id);
} else {
    $stmt = $connect->prepare("UPDATE user SET name=?, address=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $address, $id);
}
$stmt->execute();
$stmt->close();


// Update candidate table
$stmt2 = $connect->prepare("UPDATE candidate SET candidate_bio=? WHERE id=?");
$stmt2->bind_param("si", $candidate_bio, $candidate_id);

$stmt2->execute();
$stmt2->close();

header("Location: ../routes/candidate_dashboard.php");
exit;
?>



