<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/stylesheet.css" />  
  <style>
   :root {
  --bg-color: #f5f7fa;       /* default background */
  --text-color: #222;         /* default text color */
  --accent-color: #1e90ff;    /* links/buttons */
  --card-bg: #ffffff;          /* card background */
  --glass-bg: rgba(255, 255, 255, 0.15); /* glass effect */
  --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Basic reset */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: 'Segoe UI', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
  min-height: 100vh;
  overflow-x: hidden;
}

/* Header */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 60px;
  background: var(--card-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  z-index: 1000;
}
header h2 {
  font-size: 28px;
  font-weight: 700;
}

.logout-btn {
  background-color: crimson;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.logout-btn:hover {
  background-color: darkred;
  transform: scale(1.05);
}


/* Main content */
.main-content {
  padding: 80px 20px 20px 20px; /* space for fixed header */
}
.dashboard-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-between;
}

/* Cards & sections */
.admin-info, .section {
  background: var(--card-bg);
  padding: 20px;
  border-radius: 12px;
  box-shadow: var(--shadow);
  margin-bottom: 25px;
}
.admin-info {
  flex: 1;
  min-width: 250px;
  text-align: center;
}
.admin-photo {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 50%;
  margin-bottom: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

/* Section headers */
.section h3 {
  font-size: 18px;
  margin-bottom: 12px;
  border-bottom: 1px solid rgba(0,0,0,0.1);
  padding-bottom: 8px;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  font-size: 14px;
}
table th, table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid rgba(0,0,0,0.1);
}
table thead {
  background: rgba(0,0,0,0.05);
  font-weight: bold;
}
table tr:hover {
  background: rgba(0,0,0,0.03);
}
table tr.total-row {
  font-weight: bold;
  background: rgba(0,0,0,0.08);
}

/* Links */
a {
  color: var(--accent-color);
  text-decoration: none;
  font-weight: 500;
}
a:hover {
  text-decoration: underline;
}

/* Dark mode toggle */
.toggle-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: var(--card-bg);
  padding: 10px 18px;
  border-radius: 30px;
  box-shadow: var(--shadow);
  font-size: 14px;
}

/* Dark mode */
body.dark-mode {
  --bg-color: #121212;
  --text-color: #f5f5f5;
  --card-bg: #1f1f1f;
}
body.dark-mode a {
  color: #4da3ff;
}

  </style>
</head>


<?php
session_start();
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
  header("Location: ../login.html");
  exit();
}
$admin = $_SESSION['userdata'];
include '../api/connect.php';
?>
<body>
  <div id="admin-dashboard">

 <header class="dashboard-header">
  <div style="flex: 1;"></div>

  <h2 style="flex: 10; text-align: center; margin: 0;">🛠️ Admin Dashboard</h2>
    <form action="../routes/logout.php" method="POST" style="margin: 0;">
    <button type="submit" class="logout-btn">🚪 Logout</button>
  </form>
</div>
</header>

<div class="main-content">
  <div class="dashboard-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: flex-start;">
  
  <div class="admin-info" style="flex: 1; min-width: 250px;">
    <img src="../uploads/<?php echo htmlspecialchars($admin['profile_image']); ?>" alt="Admin Photo" class="admin-photo" />
    <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($admin['mobile']); ?></p>
    <p><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($admin['role'])); ?></p>
  </div>
 
<div class="section" style="flex: 2; min-width: 300px;">
  <h3>📋 All Candidates with Votes</h3>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Status</th>
        <th>Votes</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $totalVotes = 0; // initialize total votes

      // Fetch all approved candidates
      $query = mysqli_query($connect, "SELECT name, mobile, status, votes FROM user WHERE role='candidate' AND status='1'");
      while ($row = mysqli_fetch_assoc($query)) {
          $statusText = ($row['status'] == '1') ? 'Approved' : 'Pending';
          $votes = intval($row['votes']);
          $totalVotes += $votes; // add to total
          echo "<tr>
                  <td>" . htmlspecialchars($row['name']) . "</td>
                  <td>" . htmlspecialchars($row['mobile']) . "</td>
                  <td>{$statusText}</td>
                  <td>{$votes}</td>
                </tr>";
      }

      // Add a row for total votes
      echo "<tr style='font-weight:bold; background:#f0f0f0;'>
              <td colspan='3'>Total Votes</td>
              <td>{$totalVotes}</td>
            </tr>";
      ?>
    </tbody>
  </table>
</div>



<div class="section" style="flex: 2; min-width: 300px;">
    <h3>🗳️ Registered Voters</h3>
    <?php
    // Fetch voters from 'user' table
    $query = mysqli_query($connect, 
        "SELECT name, mobile, voter_id, status 
         FROM user 
         WHERE role = 'voter'"
    );

    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Voter ID</th>
                <th>Voted?</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($query)) {
        $votedText = (strtolower($row['status']) === '1') ? 'Yes' : 'No';
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['mobile']) . "</td>
                <td>" . htmlspecialchars($row['voter_id']) . "</td>
                <td>{$votedText}</td>
              </tr>";
    }

    echo "</table>";
    ?>
</div>

  <div class="section" style="flex: 2; min-width: 300px;">
    <h3>🕒 Pending Candidates</h3>
    <table>
      <thead>
        <tr><th>Name</th><th>Mobile</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='0'");

        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['mobile']}</td>
                    <td>
                      <a href='../api/approve.php?id={$row['id']}'>✅ Approve</a> | 
                      <a href='../api/reject.php?id={$row['id']}'>❌ Reject</a>
                    </td>
                  </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="section" style="flex: 2; min-width: 300px;">
  <h3>🚫 Rejected Candidates</h3>
  <table>
    <thead>
      <tr><th>Name</th><th>Mobile</th></tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='-1'");

      while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>" . htmlspecialchars($row['name']) . "</td>
                  <td>" . htmlspecialchars($row['mobile']) . "</td>
                </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</div>
<div class="toggle-container">
  <label>
    🌙 Dark Mode
    <input type="checkbox" id="darkModeToggle">
  </label>
</div>
</div>


<script>
  const toggle = document.getElementById("darkModeToggle");
  toggle.addEventListener("change", () => {
    document.body.classList.toggle("dark-mode");
  });
</script>
</body>
</html>