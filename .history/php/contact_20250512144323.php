<?php
// Include database configuration
require_once 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn->real_escape_string(string: $_POST['name']);
    $email = $conn->real_escape_string(string: $_POST['email']);
    $message = $conn->real_escape_string(string: $_POST['message']);
    $created_at = date('Y-m-d H:i:s');
    
    // Insert data into database
    $sql = "INSERT INTO messages (name, email, message, created_at) 
            VALUES ('$name', '$email', '$message', '$created_at')";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
