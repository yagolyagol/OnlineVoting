<?php include('db/connect.php'); ?>
<!DOCTYPE html>
<html>
<head><title>Results</title><link rel="stylesheet" href="css/style.css" /></head>
<body>
  <div class="container">
    <h2>Election Results</h2>
    <?php
    $results = $conn->query("SELECT * FROM candidates ORDER BY votes DESC");
    while ($row = $results->fetch_assoc()) {
      echo "<p><strong>{$row['name']}</strong> ({$row['party']}) - Votes: {$row['votes']}</p>";
    }
    ?>
    <a href="index.html" class="btn">Back to Home</a>
  </div>
</body>
</html>
