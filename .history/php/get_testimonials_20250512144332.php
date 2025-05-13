<?php
// Include database configuration
require_once 'config.php';

// Get all testimonials
$sql = "SELECT * FROM testimonials ORDER BY id DESC";
$result = $conn->query(query: $sql);

$testimonials = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

// Return as JSON
echo json_encode(value: $testimonials);

$conn->close();
?>
