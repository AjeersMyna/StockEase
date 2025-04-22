<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
require_once 'models/Product.php';
$productModel = new Product($conn);
$products = $productModel->getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Add Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-plus me-2"></i>Add New Inventory</h2>

        <form action="process_inventory.php" method="post">
            <div class="mb-3">
                <label for="product_sku">Product</label>
                <select class="form-select" id="product_sku" name="product_sku" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= htmlspecialchars($product['sku']) ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity_added">Quantity to Add</label>
                <input type="number" class="form-control" id="quantity_added" name="quantity_added" value="1" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Add to Inventory</button>
            <a href="inventory.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>