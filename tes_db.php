<?php
require_once "db.php"; // Ensure this file is in the same directory

try {
    $stmt = $conn->query("SELECT 1");
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}
?>
