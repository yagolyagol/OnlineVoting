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

  /* Header */
  #headerSection {
    background-color: rebeccapurple;
    color: white;
    padding: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(100, 0, 150, 0.3);
  }

  #headerSection h1 {
    font-weight: 700;
    font-size: 1.8rem;
    margin: 0 auto;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
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

  #Profile b {
    font-weight: 700;
    margin-top: 12px;
  }

  #Profile b + br {
    margin-bottom: 14px;
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

  /* Group cards */
  .group-card {
    border-bottom: 1px solid #ccc;
    padding: 18px 0;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: background-color 0.2s ease;
    cursor: pointer;
  }

  .group-card:hover {
    background-color: #f3eaff;
  }

  .dark-mode .group-card:hover {
    background-color: #2a2a3a;
  }

  .group-card img {
    flex-shrink: 0;
    border-radius: 15px;
    width: 100px;
    height: 100px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(100, 0, 150, 0.25);
    transition: transform 0.3s ease;
  }

  .group-card:hover img {
    transform: scale(1.05);
  }

  .group-info {
    flex-grow: 1;
  }

  .group-info b {
    font-size: 1.2rem;
    display: block;
    margin-bottom: 8px;
    font-weight: 700;
  }

  .group-info p {
    margin: 5px 0 12px;
    font-weight: 600;
  }

  /* Vote button */
  form {
    margin: 0;
  }

  #votebtn, #voted {
    background-color: rebeccapurple;
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 15px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(100, 0, 150, 0.4);
    transition: background-color 0.3s ease;
    font-size: 1rem;
  }

  #votebtn:hover {
    background-color: #5a0e9c;
  }

  #voted {
    background-color: #4caf50;
    cursor: default;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.6);
  }

  /* Responsive */
  @media (max-width: 900px) {
    #mainpanel {
      flex-direction: column;
      align-items: center;
      padding: 20px 15px;
      gap: 30px;
    }

    #Profile, #Group {
      max-width: 90vw;
      flex: 1 1 auto;
    }

    .group-card {
      flex-direction: column;
      align-items: flex-start;
    }

    .group-card img {
      margin-bottom: 12px;
      width: 90px;
      height: 90px;
    }

    #votebtn, #voted {
      width: 100%;
      padding: 12px 0;
    }
  }

  /* Dark mode toggle */
  .toggle-container {
    position: fixed;
    right: 5px;
    background: rgba(249, 249, 249, 0.85);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    color: rebeccapurple;
    user-select: none;
    box-shadow: 0 2px 10px rgba(119, 8, 174, 0.15);
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .dark-mode .toggle-container {
    background: rgba(40,40,40,0.85);
    color: #bbb;
  }

  .toggle-container input {
    margin-left: 8px;
    cursor: pointer;
    transform: scale(1.1);
    vertical-align: middle;
  }
  </style>
</head>
<body>
    <body>
  <div class="toggle-container">
    <label>
      Dark Mode
      <input type="checkbox" id="darkModeToggle" />
    </label>
  </div>

  <div id="headerSection">
    <a href="../login.html"><button id="backbutton">Back</button></a>
    <h1>Integrity Polls</h1>
  </div>

  <div id="mainpanel">
    <!-- Profile Panel -->
    <section id="Profile" aria-label="User Profile">
      <img src="../uploads/<?php echo htmlspecialchars($userdata['profile_image']); ?>" alt="User profile picture" />
      <b>Name:</b> <?php echo htmlspecialchars($userdata['name']); ?><br /><br />
      <b>Mobile:</b> <?php echo htmlspecialchars($userdata['mobile']); ?><br /><br />
      <b>Address:</b> <?php echo htmlspecialchars($userdata['address']); ?><br /><br />
      <b>Status:</b> <?php echo $status; ?><br />
    </section>

    <!-- Groups Panel -->
    <section id="Group" aria-label="Candidate Groups">
      <?php if (!empty($groupsdata)) {
        foreach ($groupsdata as $groups) { ?>
          <div class="group-card" tabindex="0" role="region" aria-label="Group <?php echo htmlspecialchars($groups['name']); ?>">
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
  <a href="logout.php"><button id="logoutbutton">Logout</button></a>
</body>

</body>
</html>
