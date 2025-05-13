<?php
// Database configuration
$host = "localhost";
$username = "";
$password = "";
$database = "portfolio_db";

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>