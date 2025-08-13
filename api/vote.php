<?php
session_start();
include("../api/connect.php");

if (isset($_POST['gvotes']) && isset($_POST['gid'])) {
    $votes = $_POST['gvotes'];
    $total_votes = $votes + 1;
    $gid = $_POST['gid'];
    $uid = $_SESSION['userdata']['id'];

    // Update candidate votes
    $update_votes = mysqli_query($connect, "UPDATE user SET votes = '$total_votes' WHERE id = '$gid'");

    // Update user's voted status in user table
    $update_user_status = mysqli_query($connect, "UPDATE user SET status = 'voted' WHERE id = '$uid'");

    // Update user's voted status in voters table
    $update_voter_status = mysqli_query($connect, "UPDATE voters SET voted = 1 WHERE user_id = '$uid'");

    if ($update_votes && $update_user_status && $update_voter_status) {
        $_SESSION['userdata']['status'] = 'voted';
        echo "<script>alert('Vote cast successfully'); window.location='../routes/voter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Vote failed'); window.location='../routes/voter_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid vote'); window.location='../routes/voter_dashboard.php';</script>";
}
?>
