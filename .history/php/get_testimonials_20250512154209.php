<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header("Content-Type: application/json");

// Include database configuration
require_once 'config.php';

// Query to get testimonials
$sql = "SELECT * FROM testimonials ORDER BY id DESC";
$result = $conn->query($sql);

$testimonials = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

// Return testimonials as JSON
echo json_encode($testimonials);

$conn->close();
?>