<?php
session_start();
include("../api/connect.php");

if (isset($_POST['gvotes']) && isset($_POST['gid'])) {
    $votes = $_POST['gvotes'];
    $total_votes = $votes + 1;
    $gid = $_POST['gid'];
    $uid = $_SESSION['userdata']['id'];

    // ✅ Step 1: Check if candidate is approved (status = 1)
    $check_candidate = mysqli_query($connect, "SELECT status FROM user WHERE id = '$gid' AND role = 'candidate'");
    $candidate = mysqli_fetch_assoc($check_candidate);

    if (!$candidate || $candidate['status'] != '1') {
        echo "<script>alert('Cannot vote: Candidate is not approved.'); window.location='../routes/voter_dashboard.php';</script>";
        exit();
    }

    // ✅ Step 2: Update candidate's vote count
    $update_votes = mysqli_query($connect, "UPDATE user SET votes = '$total_votes' WHERE id = '$gid'");

    // ✅ Step 3: Update user's status (voted)
    $update_user_status = mysqli_query($connect, "UPDATE user SET status = 'voted' WHERE id = '$uid'");
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