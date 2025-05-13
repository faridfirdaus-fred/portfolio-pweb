<?php
// php/admin_api/get_works_admin.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include database configuration
require_once '../../php/config.php';

// Get all works
$sql = "SELECT * FROM works ORDER BY id DESC";
$result = $conn->query($sql);

$works = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $works[] = $row;
    }
}

// Return JSON response
echo json_encode($works);
?>