<?php
session_start();
include("../api/connect.php");

if (isset($_POST['gvotes']) && isset($_POST['gid'])) {
    $votes = intval($_POST['gvotes']);
    $total_votes = $votes + 1;
    $gid = intval($_POST['gid']); // Candidate ID
    $uid = $_SESSION['userdata']['id']; // Voter ID

    // ✅ Step 1: Check if candidate is approved (status = 1)
    $check_candidate = mysqli_query($connect, "SELECT status FROM user WHERE id = '$gid' AND role = 'candidate'");
    $candidate = mysqli_fetch_assoc($check_candidate);

    if (!$candidate || $candidate['status'] != '1') {
        echo "<script>alert('Cannot vote: Candidate is not approved.'); window.location='../routes/voter_dashboard.php';</script>";
        exit();
    }

    // ✅ Step 2: Update votes in BOTH tables
    mysqli_query($connect, "UPDATE user SET votes = '$total_votes' WHERE id = '$gid'");
    mysqli_query($connect, "UPDATE candidate SET votes = '$total_votes' WHERE id = '$gid'");

    // ✅ Step 3: Mark voter as having voted
    mysqli_query($connect, "UPDATE user SET status = 'voted' WHERE id = '$uid'");
    mysqli_query($connect, "UPDATE voters SET voted = 1 WHERE user_id = '$uid'");

    // ✅ Step 4: Refresh session candidate data so dashboard shows updated votes
    $fetch_candidates = mysqli_query($connect, "SELECT * FROM candidate WHERE role='candidate' AND status=1");
    $_SESSION['groupsdata'] = mysqli_fetch_all($fetch_candidates, MYSQLI_ASSOC);

    $_SESSION['userdata']['status'] = 'voted';

    echo "<script>alert('Vote cast successfully'); window.location='../routes/voter_dashboard.php';</script>";
} else {
    echo "<script>alert('Invalid vote'); window.location='../routes/voter_dashboard.php';</script>";
}
?>
