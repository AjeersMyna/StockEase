<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = $_GET['id'];
$conn->query("DELETE FROM real_customers WHERE id = $id");

echo "<script>alert('Customer deleted successfully!'); window.location.href='customers.php';</script>";
?>
