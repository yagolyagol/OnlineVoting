<?php
include("../connect.php");

$data = mysqli_query($connect, "SELECT name, votes FROM user WHERE role = 'candidate'");
$names = [];
$votes = [];

while ($row = mysqli_fetch_assoc($data)) {
    $names[] = $row['name'];
    $votes[] = $row['votes'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voting Stats</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>📊 Voting Stats</h2>
    <canvas id="voteChart" width="600" height="300"></canvas>

    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($names); ?>,
                datasets: [{
                    label: 'Votes',
                    data: <?php echo json_encode($votes); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'black',
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
<?php
include '../api/connect.php';

header('Content-Type: application/json');

// Votes per candidate
$candidateData = [];
$query = mysqli_query($connect, "SELECT name, votes FROM user WHERE role='candidate' AND status='approved'");
while ($row = mysqli_fetch_assoc($query)) {
    $candidateData['names'][] = $row['name'];
    $candidateData['votes'][] = (int)$row['votes'];
}

// User roles distribution
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

// Output JSON
echo json_encode($candidateData);

