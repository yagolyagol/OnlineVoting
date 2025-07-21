<?php
include("../connect.php");

$id = $_GET['id'];
mysqli_query($connect, "UPDATE user SET status='approved' WHERE id='$id'");
header("Location: ../routes/admin_dashboard.php");
?>

<?php
session_start();
include("../api/connect.php");

// Ensure only admin can access
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Validate GET or POST
if (isset($_GET['id']) && isset($_GET['action'])) {
    $userId = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    $stmt = $connect->prepare("UPDATE user SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "User has been $status."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>
