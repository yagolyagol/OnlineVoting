<?php include('../db/connect.php'); ?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title><link rel="stylesheet" href="../css/style.css" /></head>
<body>
  <div class="container">
    <h2>Admin Dashboard</h2>
    <h3>Add Candidate</h3>
    <form method="POST">
      <input type="text" name="name" placeholder="Candidate Name" required /><br>
      <input type="text" name="party" placeholder="Party" required /><br>
      <button class="btn" type="submit" name="add">Add</button>
    </form>
    <h3>All Candidates</h3>
    <?php
    if (isset($_POST['add'])) {
      $conn->query("INSERT INTO candidates (name, party) VALUES ('{$_POST['name']}', '{$_POST['party']}')");
    }
    $candidates = $conn->query("SELECT * FROM candidates");
    while ($row = $candidates->fetch_assoc()) {
      echo "<p>{$row['name']} - {$row['party']} - Votes: {$row['votes']}</p>";
    }
    ?>
  </div>
</body>
</html>
