<?php
<?php
// Database configuration for InfinityFree
$host = "sql301.infinityfree.com";
$username = "if0_38962381";
$password = ""; // Gunakan password vPanel Anda
$database = "if0_38962381_portfolio_db";

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>