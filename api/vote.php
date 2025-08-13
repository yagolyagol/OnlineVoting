<?php
/*session_start();
include('connect.php');

$votes = $_POST['gvotes'];
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];

$total_votes = $votes + 1;

// Update votes for the selected candidate/group
$update_votes = mysqli_query($connect, "UPDATE user SET votes = '$total_votes' WHERE id = '$gid'");

// Update voter status to mark as "voted"
$update_user_status = mysqli_query($connect, "UPDATE user SET status = 1 WHERE id = '$uid'");

if ($update_votes && $update_user_status) {
    // Refresh session data
    $userdata_query = mysqli_query($connect, "SELECT * FROM user WHERE id = '$uid'");
    $_SESSION['userdata'] = mysqli_fetch_array($userdata_query, MYSQLI_ASSOC);

    $groups_query = mysqli_query($connect, "SELECT * FROM user WHERE role = 'candidate'");
    $_SESSION['groupsdata'] = mysqli_fetch_all($groups_query, MYSQLI_ASSOC);

    echo '
    <script>
        alert("Voting Successful!!");
        window.location = "../routes/dashboard.php";
    </script>';
} else {
    echo '
    <script>
        alert("Error Occurred While Voting!");
        window.location = "../routes/dashboard.php";
    </script>';
}
?>*/


/*<?php
session_start();
include("../api/connect.php");

if (isset($_POST['gvotes']) && isset($_POST['gid'])) {
    $gid = $_POST['gid']; // Candidate ID
    $uid = $_SESSION['userdata']['id']; // Voter ID

    // Check if voter already voted
    $check_vote = mysqli_query($connect, "SELECT * FROM votes WHERE voter_id='$uid'");
    if (mysqli_num_rows($check_vote) > 0) {
        echo "<script>alert('You have already voted!'); window.location='../routes/voter_dashboard.php';</script>";
        exit;
    }

    // Insert into votes table
    $insert_vote = mysqli_query($connect, "INSERT INTO votes (voter_id, candidate_id) VALUES ('$uid', '$gid')");

    // Also increment candidate's votes in user table for quick display
    $update_votes = mysqli_query($connect, "UPDATE user SET votes = votes + 1 WHERE id = '$gid'");

    // Update voter's status
    $update_status = mysqli_query($connect, "UPDATE user SET status = 'voted' WHERE id = '$uid'");

    if ($insert_vote && $update_votes && $update_status) {
        $_SESSION['userdata']['status'] = 'voted';
        echo "<script>alert('Vote cast successfully'); window.location='../routes/voter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Vote failed'); window.location='../routes/voter_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid vote'); window.location='../routes/voter_dashboard.php';</script>";
}
?>*/


session_start();
include("../api/connect.php");

if (isset($_POST['gvotes']) && isset($_POST['gid'])) {
    $votes = $_POST['gvotes'];
    $total_votes = $votes + 1;
    $gid = $_POST['gid'];
    $uid = $_SESSION['userdata']['id'];

    $update_votes = mysqli_query($connect, "UPDATE user SET votes = '$total_votes' WHERE id = '$gid'");
    $update_status = mysqli_query($connect, "UPDATE user SET status = 'voted' WHERE id = '$uid'");

    if ($update_votes && $update_status) {
        $_SESSION['userdata']['status'] = 'voted';
        echo "<script>alert('Vote cast successfully'); window.location='../routes/voter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Vote failed'); window.location='../routes/voter_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid vote'); window.location='../routes/voter_dashboard.php';</script>";
}
?>

