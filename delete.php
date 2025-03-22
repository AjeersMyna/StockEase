<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: customers.php");
?>
