<?php
include("connect.php");

   
$id = $_GET['id'];
mysqli_query($connect, "DELETE FROM user WHERE id='$id'");
header("Location: ../routes/admin_dashboard.php");
