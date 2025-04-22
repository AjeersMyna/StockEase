<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier = $_POST['supplier'];
    $purchase_date = $_POST['purchase_date'];
    $po_number = $_POST['po_number'] ?? null;
    $total_amount = $_POST['total_amount'];
    $notes = $_POST['notes'] ?? null;
    $products = $_POST['products'];

    try {
        $conn->beginTransaction();

        // Insert into the purchases table
        $stmt = $conn->prepare("INSERT INTO purchases (supplier, purchase_date, po_number, total_amount, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$supplier, $purchase_date, $po_number, $total_amount, $notes]);
        $purchase_id = $conn->lastInsertId();

        // Insert into the purchase_items table
        $stmt = $conn->prepare("INSERT INTO purchase_items (purchase_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        foreach ($products as $product) {
            $stmt->execute([$purchase_id, $product['product_id'], $product['quantity'], $product['unit_price']]);

            // Optionally update the inventory table here (increase stock quantity)
            $updateStmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE product_id = ?");
            $updateStmt->execute([$product['quantity'], $product['product_id']]);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Purchase added successfully!']);

    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error adding purchase: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>