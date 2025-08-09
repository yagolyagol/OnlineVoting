<?php
include("../api/connect.php");

$id = $_POST['id'];
$name = $_POST['name'];
$address = $_POST['address'];

if ($_FILES['profile_image']['name']) {
    $image_name = $_FILES['profile_image']['name'];
    $tmp = $_FILES['profile_image']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/$image_name");
    mysqli_query($connect, "UPDATE user SET name='$name', address='$address', profile_image='$image_name' WHERE id='$id'");
} else {
    mysqli_query($connect, "UPDATE user SET name='$name', address='$address' WHERE id='$id'");
}

header("Location: ../routes/candidate_dashboard.php");
