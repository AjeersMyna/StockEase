<?php
session_start();
require_once '../db.php';
require_once '../models/Sale.php';
require_once '../models/Product.php';

header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validate
if (empty($data['invoice_number']) || empty($data['products'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

// Process
$saleModel = new Sale($conn);
$productModel = new Product($conn);

try {
    $conn->beginTransaction();

    // 1. Create Sale
    $saleId = $saleModel->addSale([
        'invoice_number' => $data['invoice_number'],
        'customer_id' => $data['customer_id'],
        'total_amount' => $data['total_amount']
    ]);

    // 2. Add Products
    foreach ($data['products'] as $product) {
        $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$saleId, $product['id'], $product['quantity'], $product['price']]);

        // Update stock (optional)
        $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
        $stmt->execute([$product['quantity'], $product['id']]);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'sale_id' => $saleId]);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>