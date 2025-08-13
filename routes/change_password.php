<?php
session_start();
include("../api/connect.php");

// Redirect if not logged in
if (!isset($_SESSION['userdata'])) {
    header("Location: ../login.html");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $userId = $_SESSION['userdata']['id'];
    $role = $_SESSION['userdata']['role'];

    // Fetch user password
    $stmt = $connect->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        $message = "Current password is incorrect";
    } elseif (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $newPassword)) {
        $message = "New password must be at least 8 characters and contain at least one number and one special character";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "New password and confirmation do not match";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user table
        $updateStmt = $connect->prepare("UPDATE user SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        $updateStmt->execute();

        // Update role-specific table if needed
        if ($role === 'admin') {
            $updateStmt2 = $connect->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $updateStmt2->bind_param("si", $hashedPassword, $userId);
            $updateStmt2->execute();
        } elseif ($role === 'candidate') {
            $updateStmt2 = $connect->prepare("UPDATE candidate SET password = ? WHERE user_id = ?");
            $updateStmt2->bind_param("si", $hashedPassword, $userId);
            $updateStmt2->execute();
        }

        $_SESSION['userdata']['password'] = $hashedPassword;

        // Redirect based on role
        switch($role){
            case 'admin': $redirect = '../routes/admin_dashboard.php'; break;
            case 'candidate': $redirect = '../routes/candidate_dashboard.php'; break;
            default: $redirect = '../routes/voter_dashboard.php'; break;
        }

        echo "<script>alert('Password updated successfully!'); window.location='$redirect';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
.container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 350px; }
h2 { text-align: center; margin-bottom: 20px; }
input { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
button { width: 100%; padding: 10px; background: #1e90ff; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
button:hover { background: #1a7adf; }
.message { color: red; text-align: center; margin-bottom: 10px; }
.toggle-password { float: right; margin-right: 10px; cursor: pointer; user-select: none; }
.password-wrapper { position: relative; }
.password-wrapper input { width: 100%; }
.password-wrapper span { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); }
</style>
</head>
<body>

<div class="container">
  <h2>Change Password</h2>
  <?php if(!empty($message)) echo "<div class='message'>{$message}</div>"; ?>
  <form method="POST" action="">
    
    <div class="password-wrapper">
      <input type="password" name="current_password" placeholder="Current Password" required>
      <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
    </div>

    <div class="password-wrapper">
      <input type="password" name="new_password" placeholder="New Password" required>
      <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
    </div>

    <div class="password-wrapper">
      <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
      <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
    </div>

    <button type="submit">Change Password</button>
  </form>
</div>

<script>
function togglePassword(span) {
    const input = span.previousElementSibling;
    if (input.type === "password") {
        input.type = "text";
        span.textContent = "🙈";
    } else {
        input.type = "password";
        span.textContent = "👁️";
    }
}
</script>

</body>
</html>
