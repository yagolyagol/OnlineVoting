<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/stylesheet.css" />
</head>
<body>
  <!-- Dark Mode Toggle -->
  <div class="toggle-container">
    <label>
      🌙 Dark Mode
      <input type="checkbox" id="darkModeToggle">
    </label>
  </div>

  <div class="container">
    <h2>Welcome Admin</h2>

    <!-- Approved Candidates Section -->
    <div class="section">
      <h3>Approved Candidates</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Mobile</th><th>Votes</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include '../api/connect.php';
          $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='approved'");
          while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                      <td data-label='Name'>{$row['name']}</td>
                      <td data-label='Mobile'>{$row['mobile']}</td>
                      <td data-label='Votes'>{$row['votes']}</td>
                    </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Voters Section -->
    <div class="section">
      <h3>Registered Voters</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Mobile</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query($connect, "SELECT * FROM user WHERE role='voter'");
          while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                      <td data-label='Name'>{$row['name']}</td>
                      <td data-label='Mobile'>{$row['mobile']}</td>
                    </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Pending Candidates Section -->
    <div class="section">
      <h3>Pending Approvals</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Mobile</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query($connect, "SELECT * FROM user WHERE role='candidate' AND status='pending'");
          while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                      <td data-label='Name'>{$row['name']}</td>
                      <td data-label='Mobile'>{$row['mobile']}</td>
                      <td data-label='Action'>
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

  <script>
    const toggle = document.getElementById("darkModeToggle");
    toggle.addEventListener("change", () => {
      document.body.classList.toggle("dark-mode");
    });
  </script>
</body>
</html>


