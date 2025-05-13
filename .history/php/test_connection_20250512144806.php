<?php
require_once 'config.php';

if ($conn) {
    echo "<h3>Database Connection: SUCCESS ✓</h3>";

    // Show database info
    echo "<p>Connected to MySQL Server: " . $conn->host_info . "</p>";
    echo "<p>MySQL Version: " . $conn->server_info . "</p>";

    // Test if tables exist
    $tables = ['works', 'testimonials'];
    echo "<h4>Checking Tables:</h4>";
    echo "<ul>";
    foreach ($tables as $table) {
        $result = $conn->query(query: "SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<li>Table '$table': EXISTS ✓</li>";

            // Count records
            $count = $conn->query(query: "SELECT COUNT(*) as count FROM $table");
            $count_result = $count->fetch_assoc();
            echo " - Records: " . $count_result['count'];
        } else {
            echo "<li>Table '$table': MISSING ✗</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<h3>Database Connection: FAILED ✗</h3>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}
