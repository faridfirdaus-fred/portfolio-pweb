<?php
require_once 'config.php';

// Change these values
$admin_username = "admin"; // Your admin username
$new_password = "admin123"; // Your desired password

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the database
$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hashed_password, $admin_username);

if ($stmt->execute()) {
    echo "Password updated successfully!<br>";
    echo "Username: " . $admin_username . "<br>";
    echo "New Password: " . $new_password . "<br>";
    echo "New Hash: " . $hashed_password . "<br>";
    echo "<a href='login.php'>Go to Login Page</a>";
} else {
    echo "Error updating password: " . $conn->error;
}
$stmt->close();
$conn->close();
