<?php
// php/admin_api/get_message.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once 'header.php';

// Include database configuration
require_once '../../php/config.php';

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get message data
    $stmt = $conn->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}
?>