<?php
// db.php
// Database connection file

// Database credentials
$host = 'localhost';
$dbname = 'company';
$username = 'root';
$password = '';

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If there is an error, terminate the script and display the error message
    die("Connection failed: " . $conn->connect_error);
} else {
    // If the connection is successful, display a success message
    echo "Database connected successfully.";
}

// Use prepared statements to prevent SQL injection
?>