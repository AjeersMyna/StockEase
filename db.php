<?php
$host = "localhost";
$dbname = "STOCKEASE_CUSTOMERS";
$username = "root";
$password = "";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optional: Display a success message for debugging (Remove in production)
    # echo "Database connected successfully!";
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
