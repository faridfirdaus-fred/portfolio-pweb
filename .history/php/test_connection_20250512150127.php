<?php
// filepath: c:\Users\faridfred\Documents\Development\pweb\php\test_connection.php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "portfolio_db";

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection and return as JSON
header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]);
} else {
    echo json_encode(['status' => 'success', 'message' => "Connected successfully to database: $database"]);

    // Test if tables exist
    $tables = ['works', 'testimonials', 'messages'];
    $tableStatus = [];

    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        $tableStatus[$table] = $result->num_rows > 0 ? 'exists' : 'missing';
    }

    echo json_encode(['status' => 'success', 'message' => "Connected successfully", 'tables' => $tableStatus]);
}

$conn->close();
