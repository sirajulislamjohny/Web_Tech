<?php
header('Content-Type: application/json');

// DB connection settings
$host = "localhost";
$user = "root"; // your db user
$pass = "";     // your db password
$db = "sky_hospital"; // your db name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Fetch Patient Visits Data
$result = $conn->query("SELECT * FROM patient_visits");
$visits = [];
$labels = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['day'];
        $visits[] = (int)$row['visits'];
    }
} else {
    echo json_encode(['error' => 'Query failed']);
    exit;
}

$conn->close();

// Send JSON response
echo json_encode([
    'labels' => $labels,
    'visits' => $visits
]);
?>
