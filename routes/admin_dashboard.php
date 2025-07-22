<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/stylesheet.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-color);
      color: var(--text-color);
    }

    header {
      background: var(--glass-bg);
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    header h2 {
      margin: 0;
      font-size: 24px;
    }

    .admin-info {
      background: var(--glass-bg);
      padding: 20px;
      margin: 20px auto;
      max-width: 600px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    .admin-info p {
      margin: 5px 0;
    }

    .admin-photo {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      margin-bottom: 12px;
    }

    .section {
      background: var(--glass-bg);
      margin: 20px auto;
      max-width: 1000px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    table thead {
      background: rgba(255,255,255,0.1);
    }

    .toggle-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: var(--glass-bg);
  padding: 10px 18px;
  border-radius: 30px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  font-size: 14px;
}
    a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }
    .logout-btn {
  background-color: crimson;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 10px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease;
}

.logout-btn:hover {
  background-color: darkred;
}

  </style>
</head>
<body>

<?php
session_start();
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
  header("Location: ../login.html");
  exit();
}
$admin = $_SESSION['userdata'];
?>

<header>
  <h2>🛠️ Admin Dashboard</h2>
  
</header>

<div class="admin-info">
  <img src="../uploads/<?php echo htmlspecialchars($admin['profile_image']); ?>" alt="Admin Photo" class="admin-photo">
  <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
  <p><strong>Mobile:</strong> <?php echo htmlspecialchars($admin['mobile']); ?></p>
  <p><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($admin['role'])); ?></p>
</div>



<div class="section">
  <h3>✅ Approved Candidates</h3>
  <table>
    <thead>
      <tr><th>Name</th><th>Mobile</th><th>Votes</th></tr>
    </thead>
    <tbody>
      <?php
      include '../api/connect.php';
      $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='approved'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$row['name']}</td>
                  <td>{$row['mobile']}</td>
                  <td>{$row['votes']}</td>
                </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<div class="section">
  <h3>🗳️ Registered Voters</h3>
  <table>
    <thead>
      <tr><th>Name</th><th>Mobile</th></tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($connect, "SELECT * FROM user WHERE role='voter'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$row['name']}</td>
                  <td>{$row['mobile']}</td>
                </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<div class="section">
  <h3>🕒 Pending Candidates</h3>
  <table>
    <thead>
      <tr><th>Name</th><th>Mobile</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='pending'");
      while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$row['name']}</td>
                  <td>{$row['mobile']}</td>
                  <td>
                    <a href='approve.php?id={$row['id']}'>✅ Approve</a> | 
                    <a href='reject.php?id={$row['id']}'>❌ Reject</a>
                  </td>
                </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<div class="logout-section" style="text-align: right; margin-bottom: 15px;">
  <form action="../routes/logout.php" method="POST">
    <button type="submit" class="logout-btn">🚪 Logout</button>
  </form>
</div>

<div class="toggle-container">
    <label>
      🌙 Dark Mode
      <input type="checkbox" id="darkModeToggle">
    </label>
  </div>
  <script>
  const toggle = document.getElementById("darkModeToggle");
  toggle.addEventListener("change", () => {
    document.body.classList.toggle("dark-mode");
  });
</script>

</body>
</html>
