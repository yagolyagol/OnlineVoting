<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../api/connect.php");

// Check if candidate is logged in
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'candidate') {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['userdata']['id'];

// Fetch data from user + candidate tables
$query = "
    SELECT u.id, u.name, u.mobile, u.address, u.profile_image, 
           c.id AS candidate_id, c.votes, c.candidate_bio
    FROM user u
    INNER JOIN candidate c ON u.id = c.user_id
    WHERE u.id = '$user_id'
";

$result = mysqli_query($connect, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Error: Candidate data not found.";
    exit;
}

$userdata = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Candidate Dashboard</title>
<style>
/* Reset and base */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  color: #222;
  transition: background-color 0.3s ease, color 0.3s ease;
  min-height: 100vh;
  line-height: 1.5;
  padding-bottom: 40px;
}

/* Dark Mode */
body.dark-mode {
  background-color: #121212;
  color: #eee;
}

/* Header */
header {
  background-color: #007BFF;
  color: white;
  padding: 15px 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
  user-select: none;
}

header h1 {
  font-size: 1.6rem;
  flex: 1;
  text-align: center;
}

.header-controls {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  align-items: center;
  gap: 1rem;
  font-size: 0.9rem;
  white-space: nowrap;
}

/* Logout Button */
.logout-btn {
  background: #dc3545;
  padding: 8px 14px;
  border-radius: 6px;
  color: white;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.5);
  transition: background-color 0.3s ease;
}

.logout-btn:hover {
  background-color: #b52a37;
}

/* Main content container */
main {
  max-width: 900px;
  margin: 30px auto;
  padding: 0 20px 40px;
}

/* Cards */
.profile-card,
.form-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.12);
  padding: 24px 20px;
  margin-bottom: 24px;
  transition: background-color 0.3s ease;
}

body.dark-mode .profile-card,
body.dark-mode .form-card {
  background-color: #1e1e1e;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.8);
}

/* Profile Top Section */
.profile-top {
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}

.profile-top img {
  border-radius: 50%;
  width: 120px;
  height: 120px;
  object-fit: cover;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  flex-shrink: 0;
}

.profile-info {
  flex: 1;
  min-width: 220px;
}

.profile-info h2 {
  margin-bottom: 12px;
  font-weight: 700;
  font-size: 1.8rem;
}

.profile-info p {
  margin: 6px 0;
  font-weight: 500;
  font-size: 1rem;
}

/* Form styles */
form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

form input[type="text"],
form input[type="file"] {
  padding: 12px;
  border-radius: 8px;
  border: 1.5px solid #ccc;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

form input[type="text"]:focus,
form input[type="file"]:focus {
  outline: none;
  border-color: #007BFF;
  background-color: #f0f8ff;
  color: #222;
}

body.dark-mode form input[type="text"],
body.dark-mode form input[type="file"] {
  background-color: #2c2c2c;
  border-color: #555;
  color: #eee;
}

body.dark-mode form input[type="text"]:focus,
body.dark-mode form input[type="file"]:focus {
  background-color: #3a3a3a;
  border-color: #3399ff;
  color: white;
}

/* Buttons */
form button {
  padding: 14px;
  background-color: #28a745;
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

form button:hover {
  background-color: #218838;
}

body.dark-mode form button {
  background-color: #3a9a3a;
}

body.dark-mode form button:hover {
  background-color: #2f7d2f;
}

/* Responsive adjustments */
@media (max-width: 600px) {
  header h1 {
    font-size: 1.3rem;
  }
  .profile-top {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  .profile-info {
    min-width: auto;
  }
}
</style>
</head>
<body>
<header>
  <h1>Candidate Dashboard</h1>
  <div class="header-controls">
     <div style="flex:1; display:flex; justify-content:flex-end; gap:10px;">
    <a href="../routes/change_password.php">
      <button class="logout-btn" style="background:#ffa500;">🔑 Change Password</button>
    </a>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</header>

<main>
  <!-- Profile Card -->
  <div class="profile-card">
    <div class="profile-top">
      <img src="../uploads/<?php echo htmlspecialchars($userdata['profile_image']); ?>" alt="Profile Picture">
      <div class="profile-info">
        <h2><?php echo htmlspecialchars($userdata['name']); ?></h2>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($userdata['mobile']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($userdata['address']); ?></p>
        <p><strong>Total Votes:</strong> <?php echo htmlspecialchars($userdata['votes']); ?></p>
        <?php if (!empty($userdata['candidate_bio'])): ?>
          <p><strong>Bio:</strong> <?php echo htmlspecialchars($userdata['candidate_bio']); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Update Form -->
  <div class="form-card">
    <h3>Update Profile</h3>
    <form action="../api/update_candidate.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $userdata['id']; ?>">
      <input type="text" name="name" value="<?php echo htmlspecialchars($userdata['name']); ?>" placeholder="Name" required>
      <input type="text" name="address" value="<?php echo htmlspecialchars($userdata['address']); ?>" placeholder="Address" required>
      <input type="text" name="candidate_bio" value="<?php echo htmlspecialchars($userdata['candidate_bio'] ?? ''); ?>" placeholder="Bio">
      <input type="file" name="profile_image" accept="image/*">
      
      <button type="submit">Save Changes</button>
    </form>
  </div>

  <div>
    <input type="checkbox" id="darkModeToggle"> Dark Mode
  </div>
</main>

<script>
const toggle = document.getElementById('darkModeToggle');
const body = document.body;

if (localStorage.getItem('darkMode') === 'enabled') {
  body.classList.add('dark-mode');
  toggle.checked = true;
}

toggle.addEventListener('change', () => {
  if (toggle.checked) {
    body.classList.add('dark-mode');
    localStorage.setItem('darkMode', 'enabled');
  } else {
    body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', 'disabled');
  }
});
</script>
</body>
</html>
