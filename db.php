<?php
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";  // Default XAMPP password (empty)
$dbname = "STOCKEASE_CUSTOMERS";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection with better error handling
if ($conn->connect_error) {
    die("Database Connection Failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
}

// Set character set to UTF-8 (Recommended for compatibility)
$conn->set_charset("utf8");

// Optional: Display a success message for debugging (Remove in production)
# echo "Database connected successfully!";
?>
