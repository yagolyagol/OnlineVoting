<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/stylesheet.css" />
  <style>
    :root {
      --card-bg: var(--glass-bg);
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Make sure body can scroll */
html, body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: var(--bg-color);
  color: var(--text-color);
  height: 100%;
  overflow-y: auto; /* enable vertical scrolling */
}

/* Fixed header */
header {
  background: var(--glass-bg);
  width: 100%;
  padding: 15px 20px; /* reduced padding to make header smaller */
  text-align: center;      
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: fixed;  
  top: 0;
  left: 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  box-sizing: border-box;
  z-index: 1000;
}

/* Heading style */
header h2 {
  margin: 0;
  font-size: 22px;
  font-weight: bold;
}

/* Push content down so it doesn't overlap header */
.main-content {
  padding-top: 100px; /* slightly larger than header height */
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
  box-shadow: none;         /* <-- Add this */
  background-clip: padding-box;  /* Just in case */
}

.logout-btn:hover {
  background-color: darkred;
  transform: scale(1.05); /* subtle pop effect */
}

    .dashboard-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 20px;
    }

    .admin-info {
      background: var(--card-bg);
      padding: 20px;
      border-radius: 15px;
      box-shadow: var(--shadow);
      text-align: center;
      margin-bottom: 25px;
    }

    .admin-photo {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      margin-bottom: 12px;
    }

    .admin-info p {
      margin: 5px 0;
    }

    .section {
      background: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }

    .section h3 {
      margin-top: 0;
      font-size: 18px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding-bottom: 8px;
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

    a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 500;
    }
    a:hover {
      text-decoration: underline;
    }

    .toggle-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--card-bg);
      padding: 10px 18px;
      border-radius: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      font-size: 14px;
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
include '../api/connect.php';
?>
<body>

 <header>
  <div style="flex: 1;"></div>

  <h2 style="flex: 1; text-align: center; margin: 0;">🛠️ Admin Dashboard</h2>

  <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
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
  <div class="chart-container">
  <div class="chart-title">Votes per Candidate</div>
  <canvas id="votesChart"></canvas>
</div>

<div class="chart-container">
  <div class="chart-title">User Roles Distribution</div>
  <canvas id="rolesChart"></canvas>
</div>


  <div class="section" style="flex: 2; min-width: 300px;">
    <h3>✅ Approved Candidates</h3>
    <table>
       <thead>
        <tr><th>Name</th><th>Mobile</th><th>Votes</th></tr>
      </thead>
      <tbody>
        <?php
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

  <div class="section" style="flex: 2; min-width: 300px;">
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

  <div class="section" style="flex: 2; min-width: 300px;">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const votesCtx = document.getElementById('votesChart').getContext('2d');
new Chart(votesCtx, {
  type: 'bar',
  data: {
    labels: ['Candidate A', 'Candidate B', 'Candidate C'],
    datasets: [{
      label: 'Votes',
      data: [120, 90, 75],
      backgroundColor: ['#4CAF50', '#2196F3', '#FFC107']
    }]
  }
});

const rolesCtx = document.getElementById('rolesChart').getContext('2d');
new Chart(rolesCtx, {
  type: 'pie',
  data: {
    labels: ['Voters', 'Candidates', 'Admins'],
    datasets: [{
      data: [200, 10, 2],
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
    }]
  }
});
</script>

</body>
</html>

 