<?php
session_start();
require_once 'db.php';
require_once 'models/Sale.php';
require_once 'models/Customer.php';
require_once 'models/Product.php';

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header("Location: sales.php");
    exit;
}

$saleId = (int)$_GET['id'];
$saleModel = new Sale($conn);
$sale = $saleModel->getSaleById($saleId);

if (!$sale) {
    header("Location: sales.php");
    exit;
}

// Fetch sale items
$stmt = $conn->prepare("
    SELECT p.name, p.sku, si.quantity, si.unit_price
    FROM sale_items si
    JOIN products p ON si.product_id = p.id
    WHERE si.sale_id = ?
");
$stmt->execute([$saleId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Sale - StockEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('sidebar.php'); ?>
    
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between mb-4">
            <h2>Sale #<?= htmlspecialchars($sale['invoice_number']) ?></h2>
            <div>
                <a href="generate_invoice.php?id=<?= $sale['id'] ?>" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Generate Invoice
                </a>
                <a href="sales.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Sale Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($sale['sale_date'])) ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?= $sale['status'] == 'completed' ? 'success' : 'warning' ?>">
                                <?= ucfirst($sale['status']) ?>
                            </span>
                        </p>
                        <p><strong>Customer:</strong> 
                            <?= $sale['customer_name'] ?? 'Walk-in Customer' ?>
                        </p>
                        <p><strong>Total Amount:</strong> ₹<?= number_format($sale['total_amount'], 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Items</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['sku']) ?></td>
                            <td class="text-end">₹<?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-end"><?= $item['quantity'] ?></td>
                            <td class="text-end">₹<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-active">
                            <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                            <td class="text-end"><strong>₹<?= number_format($sale['total_amount'], 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>