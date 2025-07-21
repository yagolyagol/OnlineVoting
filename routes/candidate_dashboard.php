<?php
session_start();
include("../connect.php");
$_SESSION['userdata']['role'] === 'admin' or 'voter' or 'candidate';


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
            margin: 20px;
            background-color: #f4f4f4;
            transition: background-color 0.3s, color 0.3s;
        }

        .dark-mode {
            background-color: #121212;
            color: #eee;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .dark-mode .container {
            background-color: #1e1e1e;
        }

        img {
            border-radius: 50%;
        }

        .toggle-container {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .toggle-container label {
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="toggle-container">
        <label>
            <input type="checkbox" id="darkModeToggle"> Dark Mode
        </label>
    </div>

    <div class="container">
         <form action="../api/update_candidate.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $userdata['id']; ?>">
        Name: <input type="text" name="name" value="<?php echo $userdata['name']; ?>"><br><br>
        Address: <input type="text" name="address" value="<?php echo $userdata['address']; ?>"><br><br>
        Profile Image: <input type="file" name="profile_image"><br><br>
        <button type="submit">Update Profile</button>
    </form>
        <center>
            <h2>Welcome, Candidate <?php echo $userdata['name']; ?></h2>
            <img src="../uploads/<?php echo $userdata['profile_image']; ?>" width="120" height="120"><br><br>
        </center>
        <p><strong>Mobile:</strong> <?php echo $userdata['mobile']; ?></p>
        <p><strong>Address:</strong> <?php echo $userdata['address']; ?></p>
        <p><strong>Total Votes:</strong> <?php echo $userdata['votes']; ?></p>
        <p><a href="logout.php">Logout</a></p>
    </div>

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
