<?php
session_start();

$_SESSION['userdata']['role'] === 'admin' or 'voter' or 'candidate';

if (!isset($_SESSION['userdata']) || !isset($_SESSION['groupsdata'])) {
    header("Location: ../login.html");
    exit;
}
if ($_SESSION['userdata']['role'] !== 'voter') {
    header("Location: ../login.html");
    exit;
}


$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];

$status = ($userdata['status'] == 0)
    ? '<b style="color:red">Not Voted</b>'
    : '<b style="color:green">Voted</b>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Integrity Polls - Dashboard</title>
  <link rel="stylesheet" href="../css/stylesheet.css" />
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Inter", sans-serif;
      background: linear-gradient(to right, #e3f2fd, #bbdefb);
      min-height: 100vh;
    }

    #headerSection {
      padding: 20px;
      background-color: rebeccapurple;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    #headerSection h1 {
      margin: 0 auto;
    }

    #backbutton, #logoutbutton {
      padding: 10px 16px;
      border: none;
      border-radius: 10px;
      background-color: #fff;
      color: rebeccapurple;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    #backbutton:hover, #logoutbutton:hover {
      background-color: #eee;
    }

    #mainpanel {
      display: flex;
      flex-wrap: wrap;
      padding: 20px;
      gap: 20px;
    }

    #Profile, #Group {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    #Profile {
      flex: 1 1 300px;
      max-width: 350px;
    }

    #Group {
      flex: 2 1 600px;
      overflow-y: auto;
      max-height: 80vh;
    }

    .group-card {
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 15px;
    }

    .group-card img {
      float: right;
      border-radius: 8px;
    }

    #votebtn, #voted {
      padding: 10px;
      border: none;
      border-radius: 10px;
      color: white;
      cursor: pointer;
      margin-top: 10px;
    }

    #votebtn {
      background-color: rebeccapurple;
    }

    #voted {
      background-color: green;
    }

    @media (max-width: 768px) {
      #mainpanel {
        flex-direction: column;
        align-items: center;
      }
      #Profile, #Group {
        width: 90%;
      }
    }
    body {
    background-color: #f0f0f0;
    color: #111;
    transition: background-color 0.3s, color 0.3s;
}

.dark-mode {
    background-color: #121212;
    color: #eee;
}

.dark-mode #Profile,
.dark-mode #Group {
    background-color: #1e1e1e;
    color: #ddd;
}

.dark-mode #votebtn {
    background-color: #6a0dad;
}

.dark-mode #voted {
    background-color: #2e7d32;
}

.toggle-container {
    position: fixed;
    top: 15px;
    right: 15px;
}

.toggle-container label {
    cursor: pointer;
    font-size: 14px;
}

  </style>
</head>
<body>
    <div class="toggle-container">
    <label>
        <input type="checkbox" id="darkModeToggle" />
        Dark Mode
    </label>
</div>

  <div id="headerSection">
    <a href="../login.html"><button id="backbutton">Back</button></a>
    <h1>Integrity Polls</h1>
    <a href="logout.php"><button id="logoutbutton">Logout</button></a>
  </div>

  <div id="mainpanel">
    <!-- Profile Panel -->
    <div id="Profile">
      <center>
        <img src="../uploads/<?php echo $userdata['profile_image']; ?>" width="100" height="100" style="border-radius: 50%;">
      </center><br>
      <b>Name:</b> <?php echo $userdata['name']; ?><br><br>
      <b>Mobile:</b> <?php echo $userdata['mobile']; ?><br><br>
      <b>Address:</b> <?php echo $userdata['address']; ?><br><br>
      <b>Status:</b> <?php echo $status; ?><br><br>
    </div>

    <!-- Groups Panel -->
    <div id="Group">
      <?php
      if (!empty($groupsdata)) {
          foreach ($groupsdata as $groups) {
              ?>
              <div class="group-card">
                <img src="../uploads/<?php echo $groups['profile_image']; ?>" height="100" width="100">
                <b>Group Name:</b> <?php echo $groups['name']; ?><br><br>
                <b>Votes:</b> <?php echo $groups['votes']; ?><br><br>
                <form action="../api/vote.php" method="POST">
                  <input type="hidden" name="gvotes" value="<?php echo $groups['votes']; ?>">
                  <input type="hidden" name="gid" value="<?php echo $groups['id']; ?>">
                  <?php if ($userdata['status'] == 0) { ?>
                    <input type="submit" name="votebtn" value="Vote" id="votebtn">
                  <?php } else { ?>
                    <button disabled type="button" id="voted">Voted</button>
                  <?php } ?>
                </form>
              </div>
              <?php
          }
      } else {
          echo "<p>No candidate groups found.</p>";
      }
      ?>
    </div>
  </div>
  <script>
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Load saved mode
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
