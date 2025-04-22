<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_sku = $_POST['product_sku'];
    $quantity_added = $_POST['quantity_added'];

    try {
        // Check if the product exists in the inventory table based on SKU
        $stmtCheck = $conn->prepare("SELECT * FROM inventory WHERE sku = ?");
        $stmtCheck->execute([$product_sku]);
        $inventoryItem = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($inventoryItem) {
            // If it exists, update the quantity
            $stmtUpdate = $conn->prepare("UPDATE inventory SET quantity = quantity + ? WHERE sku = ?");
            $stmtUpdate->execute([$quantity_added, $product_sku]);
            header("Location: inventory.php?success=1");
        } else {
            // If it doesn't exist in inventory
            header("Location: inventory.php?error=productnotfoundininventory");
        }
        exit();

    } catch (PDOException $e) {
        header("Location: inventory.php?error=" . urlencode("Database error: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: inventory.php");
    exit();
}
?>