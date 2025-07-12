<?php
session_start();
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
?>


