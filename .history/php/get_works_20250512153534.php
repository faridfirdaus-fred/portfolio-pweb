<?php

require_once 'config.php';

// Get all work items
$sql = "SELECT * FROM works ORDER BY id DESC";
$result = $conn->query($sql);

$works = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $works[] = $row;
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($works);

$conn->close();
