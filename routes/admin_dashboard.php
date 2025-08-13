<?php
session_start();
include '../api/connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Admin info from admin table (stored in session after login)
$admin = $_SESSION['userdata'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/stylesheet.css" />
<style>
:root {
  --bg-color: #f5f7fa;
  --text-color: #222;
  --accent-color: #1e90ff;
  --card-bg: #ffffff;
  --glass-bg: rgba(255, 255, 255, 0.15);
  --shadow: 0 4px 12px rgba(0,0,0,0.1);
}
* { box-sizing: border-box; margin:0; padding:0; }
body { font-family:'Segoe UI', sans-serif; background-color: var(--bg-color); color: var(--text-color); min-height:100vh; overflow-x:hidden; }
header { position:fixed; top:0; left:0; width:100%; height:60px; background:var(--card-bg); display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); z-index:1000;}
header h2 { font-size:28px; font-weight:700; }
.logout-btn { 
  background-color: crimson;
  color:#fff; 
  border:none; 
  padding:8px 16px; 
  border-radius:8px; 
  font-weight:bold; 
  cursor:pointer; 
  transition:all 0.3s ease; }
.logout-btn:hover { background-color: darkred; transform: scale(1.05);}
.main-content { padding: 80px 20px 20px 20px; }
.dashboard-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:space-between; }
.admin-info, .section { background:var(--card-bg); padding:20px; border-radius:12px; box-shadow: var(--shadow); margin-bottom:25px; }
.admin-info { flex:1; min-width:250px; text-align:center; }
.admin-photo { width: 95px; height:95px; object-fit:cover; border-radius:50%; margin-bottom:12px; box-shadow:0 2px 6px rgba(0,0,0,0.2);}
.section h3 { font-size:18px; margin-bottom:12px; border-bottom:1px solid rgba(0,0,0,0.1); padding-bottom:8px; }
table { width:100%; border-collapse:collapse; margin-top:10px; font-size:14px; }
table th, table td { padding:10px; text-align:left; border-bottom:1px solid rgba(0,0,0,0.1); }
table thead { background: rgba(0,0,0,0.05); font-weight:bold; }
table tr:hover { background: rgba(0,0,0,0.03); }
a { color: var(--accent-color); text-decoration:none; font-weight:500;}
a:hover { text-decoration: underline;}
.toggle-container { position:fixed; bottom:20px; right:20px; background: var(--card-bg); padding:10px 18px; border-radius:30px; box-shadow:var(--shadow); font-size:14px;}
body.dark-mode { --bg-color:#121212; --text-color:#f5f5f5; --card-bg:#1f1f1f; }
body.dark-mode a { color:#4da3ff; }

.admin-info {
  background-color: var(--card-bg);
  color: var(--text-color);
  max-width: 320px;
  margin: 30px auto;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  text-align: center;
  overflow: hidden;
  font-family: 'Poppins', sans-serif;
  
}

.profile-info {
  color: var(--text-color);
}

.profile-name {
  color: var(--text-color);
}

.admin-info:hover {
  transform: translateY(-5px);
}

.profile-image {
  padding: 20px;
  background: linear-gradient(135deg, #667eea, #764ba2);
}

.profile-image img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 50%;
  border: 4px solid #fff;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.profile-info p {
  margin: 8px 0;
  font-size: 0.95rem;
  color: #555;
}

.role-badge {
  margin-top: 12px;
  display: inline-block;
  padding: 5px 14px;
  background-color: #667eea;
  color: #fff;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.pagination {
    margin-top: 15px;
    text-align: center;
}
.pagination a {
    padding: 5px 10px;
    margin: 0 3px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-decoration: none;
    color: #1e90ff;
}
.pagination a:hover {
    background-color: #1e90ff;
    color: white;
}
.pagination strong {
    padding: 5px 10px;
    margin: 0 3px;
    border-radius: 5px;
    background-color: #1e90ff;
    color: white;
}

</style>
</head>
<body>

<div id="admin-dashboard">
<header class="dashboard-header">
  <div style="flex: 1;"></div>
  <h2 style="flex: 10; text-align: center; margin: 0;">🛠️ Admin Dashboard</h2>
  <div style="flex:1; display:flex; justify-content:flex-end; gap:10px;">
    <a href="../routes/change_password.php">
      <button  style="background:#ffa500;">🔑 Change Password</button>
    </a>
  <form action="../routes/logout.php" method="POST" style="margin: 0;">
    <button type="submit" class="logout-btn">🚪 Logout</button>
  </form>
</header>

<div class="main-content">
<div class="dashboard-container">

  <!-- Admin Info -->
  <div class="admin-info">
    <img src="../uploads/<?php echo htmlspecialchars($admin['profile_image']); ?>" alt="Admin Photo" class="admin-photo" />
    <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($admin['mobile']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($admin['address']); ?></p>
    <p><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($admin['role'])); ?></p>
  </div>

  <!-- Candidates with Votes -->
  <div class="section" style="flex:2; min-width:300px;">
    <h3>📋 All Candidates with Votes</h3>
    <table>
      <tbody>


      <?php
// 1️⃣ Pagination variables
$limit = 5; // number of candidates per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1; // ensure page is at least 1
$offset = ($page - 1) * $limit;

// 2️⃣ Get total number of candidates
$total_query = mysqli_query($connect, "SELECT COUNT(*) as total FROM user WHERE role='candidate' AND status='1'");
$total_row = mysqli_fetch_assoc($total_query);
$total_records = $total_row['total'];

// 3️⃣ Prevent division by zero
$total_pages = ($total_records > 0) ? ceil($total_records / $limit) : 1;

// 4️⃣ Fetch candidates for current page
$query = mysqli_query($connect, "SELECT name, mobile, status, votes FROM user WHERE role='candidate' AND status='1' LIMIT $limit OFFSET $offset");

// 5️⃣ Display table
$totalVotes = 0;
echo "<table>
        <thead>
          <tr><th>Name</th><th>Mobile</th><th>Status</th><th>Votes</th></tr>
        </thead>
        <tbody>";
while ($row = mysqli_fetch_assoc($query)) {
    $statusText = ($row['status'] == '1') ? 'Approved' : 'Pending';
    $votes = intval($row['votes']);
    $totalVotes += $votes;
    echo "<tr>
            <td>".htmlspecialchars($row['name'])."</td>
            <td>".htmlspecialchars($row['mobile'])."</td>
            <td>{$statusText}</td>
            <td>{$votes}</td>
          </tr>";
}
echo "<tr class='total-row'>
        <td colspan='3'>Total Votes</td>
        <td>{$totalVotes}</td>
      </tr>";
echo "</tbody></table>";

// 6️⃣ Pagination links
echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<strong>$i</strong> ";
    } else {
        echo "<a href='?page=$i'>$i</a> ";
    }
}
echo "</div>";
?>
      </tbody>
    </table>
  </div>

 <div class="section" style="flex: 2; min-width: 300px;">
  <h3>🗳️ Registered Voters</h3>
  <?php
  // 1️⃣ Pagination variables
  $limit = 5; // number of voters per page
  $page = isset($_GET['voter_page']) ? (int)$_GET['voter_page'] : 1;
  if ($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  // 2️⃣ Total voters count
  $total_query = mysqli_query($connect, 
      "SELECT COUNT(*) as total 
       FROM user u
       JOIN voters v ON u.id = v.user_id
       WHERE u.role='voter'"
  );
  $total_row = mysqli_fetch_assoc($total_query);
  $total_records = $total_row['total'];
  $total_pages = ($total_records > 0) ? ceil($total_records / $limit) : 1;

  // 3️⃣ Fetch voters for current page
  $query = mysqli_query($connect, 
    "SELECT u.name, u.mobile, v.voter_id, v.voted 
     FROM user u
     JOIN voters v ON u.id = v.user_id
     WHERE u.role = 'voter'
     LIMIT $limit OFFSET $offset"
  );

  // 4️⃣ Display table
  echo "<table border='1'>
          <thead>
            <tr>
              <th>Name</th>
              <th>Mobile</th>
              <th>Voter ID</th>
              <th>Voted?</th>
            </tr>
          </thead>
          <tbody>";

  while ($row = mysqli_fetch_assoc($query)) {
      $votedText = ($row['voted'] == 1) ? 'Yes' : 'No';
      echo "<tr>
              <td>" . htmlspecialchars($row['name']) . "</td>
              <td>" . htmlspecialchars($row['mobile']) . "</td>
              <td>" . htmlspecialchars($row['voter_id']) . "</td>
              <td>{$votedText}</td>
            </tr>";
  }

  echo "</tbody></table>";

  // 5️⃣ Pagination links
  echo "<div class='pagination'>";
  for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == $page) {
          echo "<strong>$i</strong> ";
      } else {
          echo "<a href='?voter_page=$i'>$i</a> ";
      }
  }
  echo "</div>";
  ?>
</div>






  <!-- Pending Candidates -->
  <div class="section" style="flex:2; min-width:300px;">
    <h3>🕒 Pending Candidates</h3>
    <table>
      <thead><tr><th>Name</th><th>Mobile</th><th>Action</th></tr></thead>
      <tbody>
      <?php
        $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='0'");
        while($row=mysqli_fetch_assoc($query)){
          echo "<tr>
                  <td>".htmlspecialchars($row['name'])."</td>
                  <td>".htmlspecialchars($row['mobile'])."</td>
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

  <!-- Rejected Candidates -->
  <div class="section" style="flex:2; min-width:300px;">
    <h3>🚫 Rejected Candidates</h3>
    <table>
      <thead><tr><th>Name</th><th>Mobile</th></tr></thead>
      <tbody>
      <?php
        $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='-1'");
        while($row=mysqli_fetch_assoc($query)){
          echo "<tr>
                  <td>".htmlspecialchars($row['name'])."</td>
                  <td>".htmlspecialchars($row['mobile'])."</td>
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
