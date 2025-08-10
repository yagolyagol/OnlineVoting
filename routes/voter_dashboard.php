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
  
 
 
  <style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    min-height: 100vh;
    color: #111;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  /* Dark Mode */
  .dark-mode {
    background: #121212;
    color: #eee;
  }

  /* Dark mode overrides */
body.dark-mode {
  --bg-color: #121212;
  --text-color: #eee;
  --glass-bg: rgba(30, 30, 30, 0.6);
  --shadow: rgba(255, 255, 255, 0.1);
  background-color: var(--bg-color);
  color: var(--text-color);
}

/* Also apply to containers/tables/cards if needed */
.container,
.section,
.admin-info {
  background-color: var(--glass-bg);
  color: var(--text-color);
}

.container {
  max-width: 1100px;
  margin: 30px auto;
  padding: 20px;
}

/* Section boxes */
.section {
  background-color: #ffffff;
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
  transition: background-color 0.3s;
}

body.dark-mode .section {
  background-color: #1e1e1e;
}

  /* HEADER BAR */
  #headerBar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rebeccapurple;
    padding: 10px 20px;
    color: white;
    box-shadow: 0 4px 8px rgba(100, 0, 150, 0.3);
  }

  #headerBar h1 {
    flex: 1;
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin: 0;
  }

  .header-left,
  .header-right {
    display: flex;
    align-items: center;
  }

  /* Buttons */
  #backbutton, #logoutbutton {
    background-color: crimson;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  #backbutton:hover, #logoutbutton:hover {
    background-color: #e6e6e6;
    color: black;
  }

  .dark-mode #backbutton,
  .dark-mode #logoutbutton {
    background-color: #2c2c2c;
    color: white;
    border: 1px solid #555;  
  }

  .dark-mode #backbutton:hover,
  .dark-mode #logoutbutton:hover {
    background-color: #444;
  }

  /* Main container */
  #mainpanel {
    display: flex;
    flex-wrap: wrap;
    padding: 25px 30px;
    gap: 24px;
    max-width: 1100px;
    margin: 0 auto 60px;
  }

  /* Profile Panel */
  #Profile {
    flex: 1 1 320px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 20px rgba(100, 0, 150, 0.12);
    padding: 30px 25px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .dark-mode #Profile {
    background: #1e1e1e;
    color: #ddd;
  }

  #Profile img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 3px solid rebeccapurple;
    margin-bottom: 20px;
    box-shadow: 0 3px 8px rgba(100, 0, 150, 0.4);
    object-fit: cover;
  }

  /* Groups Panel */
  #Group {
    flex: 2 1 650px;
    background: #fff;
    border-radius: 20px;
    padding: 30px 25px;
    box-shadow: 0 10px 25px rgba(100, 0, 150, 0.12);
    max-height: 80vh;
    overflow-y: auto;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .dark-mode #Group {
    background: #1e1e1e;
    color: #ddd;
  }

  .group-card {
    border-bottom: 1px solid #ccc;
    padding: 18px 0;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: background-color 0.2s ease;
  }

  .group-card:hover {
    background-color: #af91d6ff;
  }

  .dark-mode .group-card:hover {
    background-color: #2a2a3a;
  }

  .group-card img {
    border-radius: 15px;
    width: 100px;
    height: 100px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(100, 0, 150, 0.25);
  }

  .group-info {
    flex-grow: 1;
  }

  /* Vote button */
  #votebtn, #voted {
    background-color: rebeccapurple;
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 15px;
    font-weight: 700;
    cursor: pointer;
    font-size: 1rem;
  }

  #votebtn:hover {
    background-color: #5a0e9c;
  }

  #voted {
    background-color: #4caf50;
    cursor: default;
  }

  /* Dark mode toggle */
  .toggle-container {
  position: fixed;
  bottom: 20px;   /* Distance from the bottom */
  right: 20px;    /* Distance from the right */
  background: rgba(249, 249, 249, 0.85);
  padding: 8px 16px;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 600;
  color: rebeccapurple;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15); /* optional shadow for visibility */
  z-index: 1000; /* ensure it's above other elements */
}

.dark-mode .toggle-container {
  background: rgba(40,40,40,0.85);
  color: #bbb;
}
  </style>
</head>

<body>

  <div class="toggle-container">
    <label>
      Dark Mode
      <input type="checkbox" id="darkModeToggle" />
    </label>
  </div>

  <!-- HEADER -->
  <div id="headerBar">
    <div class="header-left">
      <a href="../login.html"><button id="backbutton">Back</button></a>
    </div>
    <h1>Integrity Polls</h1>
    <div class="header-right">
      <a href="logout.php"><button id="logoutbutton">Logout</button></a>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div id="mainpanel">
    <!-- Profile -->
    <section id="Profile">
      <img src="../uploads/<?php echo htmlspecialchars($userdata['profile_image']); ?>" alt="User profile picture" />
      <b>Name:</b> <?php echo htmlspecialchars($userdata['name']); ?><br /><br />
      <b>Mobile:</b> <?php echo htmlspecialchars($userdata['mobile']); ?><br /><br />
      <b>Address:</b> <?php echo htmlspecialchars($userdata['address']); ?><br /><br />
      <b>Status:</b> <?php echo $status; ?><br />
    </section>

    <!-- Groups -->
    <section id="Group">
      <?php if (!empty($groupsdata)) {
        foreach ($groupsdata as $groups) { ?>
          <div class="group-card">
            <img src="../uploads/<?php echo htmlspecialchars($groups['profile_image']); ?>" alt="Group <?php echo htmlspecialchars($groups['name']); ?> image" />
            <div class="group-info">
              <b><?php echo htmlspecialchars($groups['name']); ?></b>
              <p>Votes: <?php echo htmlspecialchars($groups['votes']); ?></p>
              <form action="../api/vote.php" method="POST">
                <input type="hidden" name="gvotes" value="<?php echo htmlspecialchars($groups['votes']); ?>" />
                <input type="hidden" name="gid" value="<?php echo htmlspecialchars($groups['id']); ?>" />
                <?php if ($userdata['status'] == 0) { ?>
                  <input type="submit" id="votebtn" value="Vote" />
                <?php } else { ?>
                  <button id="voted" disabled>Voted</button>
                <?php } ?>
              </form>
            </div>
          </div>
      <?php }
      } else {
        echo "<p>No candidate groups found.</p>";
      } ?>
    </section>
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
