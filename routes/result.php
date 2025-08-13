<?php
header('Content-Type: application/json');
include("../api/connect.php"); // adjust path if needed

$candidateData = [];

// 1️⃣ Fetch candidate names and votes
$query = mysqli_query($connect, "SELECT name, votes FROM user WHERE role='candidate' AND status='approved'");
$candidateData['names'] = [];
$candidateData['votes'] = [];

while ($row = mysqli_fetch_assoc($query)) {
    $candidateData['names'][] = $row['name'];
    $candidateData['votes'][] = (int)$row['votes'];
}

// 2️⃣ Fetch user roles distribution
$roles = ['voter', 'candidate', 'admin'];
$rolesCount = [];

foreach ($roles as $role) {
    $countQuery = mysqli_query($connect, "SELECT COUNT(*) AS count FROM user WHERE role='$role'");
    $countRow = mysqli_fetch_assoc($countQuery);
    $rolesCount[] = (int)$countRow['count'];
}

$candidateData['roles'] = [
    'labels' => ['Voters', 'Candidates', 'Admins'],
    'counts' => $rolesCount
];

// 3️⃣ Output as JSON
echo json_encode($candidateData);
?>
