<?php
session_start();
include("../api/connect.php");

// Check if candidate is logged in
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] !== 'candidate') {
    header("Location: ../login.html");
    exit;
}
$userdata = $_SESSION['userdata'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Dark Mode */
        .dark-mode {
            background-color: #121212;
            color: #eee;
        }

        header {
            background: #007BFF;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        header h1 {
            font-size: 1.4rem;
            margin: 0;
            flex: 1;
            text-align: center;
        }
        .header-controls {
            position: absolute;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .dark-mode header {
            background: #1f1f1f;
        }

        main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .profile-card, .form-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            padding: 20px;
            margin-bottom: 20px;
        }

        .dark-mode .profile-card,
        .dark-mode .form-card {
            background-color: #1e1e1e;
        }

        .profile-top {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-top img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .profile-info p {
            margin: 5px 0;
        }

        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        form button:hover {
            background: #218838;
        }

       /* Style logout button */
.logout-btn {
  background: #dc3545;
  padding: 10px 15px;
  border-radius: 6px;
  color: white;
  text-decoration: none;
  cursor: pointer;
}

        .logout-btn:hover {
            background: #b52a37;
        }

        /* Dark Mode Form Styles */
        .dark-mode input, .dark-mode button {
            background-color: #2c2c2c;
            color: white;
            border: 1px solid #555;
        }
    </style>
</head>
<body>
  <header>
  <h1>Candidate Dashboard</h1>
  <div class="header-controls">
    <label style="font-size:14px;">
      <input type="checkbox" id="darkModeToggle"> Dark Mode
    </label>
  </div>
</header>



    <main>
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-top">
                <img src="../uploads/<?php echo $userdata['profile_image']; ?>" alt="Profile Picture">
                <div class="profile-info">
                    <h2><?php echo $userdata['name']; ?></h2>
                    <p><strong>Mobile:</strong> <?php echo $userdata['mobile']; ?></p>
                    <p><strong>Address:</strong> <?php echo $userdata['address']; ?></p>
                    <p><strong>Total Votes:</strong> <?php echo $userdata['votes']; ?></p>
                </div>
            </div>
        </div>

        <!-- Update Form -->
        <div class="form-card">
            <h3>Update Profile</h3>
            <form action="../api/update_candidate.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $userdata['id']; ?>">
                <input type="text" name="name" value="<?php echo $userdata['name']; ?>" placeholder="Name">
                <input type="text" name="address" value="<?php echo $userdata['address']; ?>" placeholder="Address">
                <input type="file" name="profile_image">
                <button type="submit">Save Changes</button>
            </form>
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
      <a href="logout.php" class="logout-btn">Logout</a>
</body>
</html>
