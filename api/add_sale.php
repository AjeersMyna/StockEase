<?php
require_once '../db.php';

// Ensure it's a POST request and content type is JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['invoice_number']) || empty($input['invoice_number']) ||
    !isset($input['total_amount']) || !is_numeric($input['total_amount']) || $input['total_amount'] < 0 ||
    !isset($input['products']) || !is_array($input['products']) || empty($input['products'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid data']);
    exit();
}

$invoiceNumber = $input['invoice_number'];
$customerId = isset($input['customer_id']) && is_numeric($input['customer_id']) ? (int)$input['customer_id'] : null;
$totalAmount = $input['total_amount'];
$products = $input['products'];

try {
    $conn->beginTransaction();

    // 1. Insert into the 'sales' table
    $stmt = $conn->prepare("INSERT INTO sales (invoice_number, customer_id, total_amount, sale_date) VALUES (:invoice_number, :customer_id, :total_amount, NOW())");
    $stmt->bindParam(':invoice_number', $invoiceNumber);
    $stmt->bindParam(':customer_id', $customerId);
    $stmt->bindParam(':total_amount', $totalAmount);
    $stmt->execute();
    $saleId = $conn->lastInsertId();

    // 2. Insert into the 'sale_items' table
    $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (:sale_id, :product_id, :quantity, :unit_price)");
    foreach ($products as $product) {
        if (!isset($product['id']) || !is_numeric($product['id']) || $product['id'] <= 0 ||
            !isset($product['quantity']) || !is_numeric($product['quantity']) || $product['quantity'] <= 0 ||
            !isset($product['price']) || !is_numeric($product['price']) || $product['price'] < 0) {
            $conn->rollBack();
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid product data']);
            exit();
        }
        $stmt->bindParam(':sale_id', $saleId);
        $stmt->bindParam(':product_id', $product['id']);
        $stmt->bindParam(':quantity', $product['quantity']);
        $stmt->bindParam(':unit_price', $product['price']);
        $stmt->execute();

        // Optionally, you might want to update the stock quantity in the 'products' table here
        $updateStockStmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :product_id AND stock_quantity >= :quantity");
        $updateStockStmt->bindParam(':quantity', $product['quantity']);
        $updateStockStmt->bindParam(':product_id', $product['id']);
        $updateStockStmt->execute();

        if ($updateStockStmt->rowCount() === 0) {
            $conn->rollBack();
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Insufficient stock for product ID: ' . $product['id']]);
            exit();
        }
    }

    $conn->commit();

    http_response_code(201); // Created
    echo json_encode(['success' => true, 'sale_id' => $saleId]);

} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>