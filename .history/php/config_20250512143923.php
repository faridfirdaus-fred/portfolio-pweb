<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "portfolio_db";

// Create database connection
$conn = new mysqli(hostname: "localhost", username: $username, password: $password, database: $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
