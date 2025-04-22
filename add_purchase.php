<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
require_once 'models/Product.php'; // Ensure this path is correct
$productModel = new Product($conn);
$products = $productModel->getProducts(); // Fetch products

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Add Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .product-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-plus me-2"></i>Add New Purchase</h2>

        <form id="addPurchaseForm">
            <div class="mb-3">
                <label for="supplier">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier" required>
            </div>
            <div class="mb-3">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" required>
            </div>

            <div id="product-list">
                <div class="product-item">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_id_0">Product</label>
                            <select class="form-select product-select" id="product_id_0" name="products[0][product_id]" required>
                                <option value="">Select Product</option>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku'] ?? '') ?>)</option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No products available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quantity_0">Quantity</label>
                            <input type="number" class="form-control" id="quantity_0" name="products[0][quantity]" value="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="unit_price_0">Unit Price</label>
                            <input type="number" class="form-control unit-price-input" id="unit_price_0" name="products[0][unit_price]" step="0.01" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-success mb-3" id="addProductRow">
                <i class="fas fa-plus"></i> Add Product
            </button>

            <div class="mb-3">
                <label for="total_amount">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" readonly>
            </div>
            <div class="mb-3">
                <label for="po_number">Purchase Order # (Optional)</label>
                <input type="text" class="form-control" id="po_number" name="po_number">
            </div>
            <div class="mb-3">
                <label for="notes">Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Purchase</button>
            <a href="purchase.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let productCounter = 1;
    const productList = document.getElementById('product-list');
    const addProductRowBtn = document.getElementById('addProductRow');
    const totalAmountInput = document.getElementById('total_amount');
    const addPurchaseForm = document.getElementById('addPurchaseForm');

    addProductRowBtn.addEventListener('click', function() {
        const newProductItem = document.createElement('div');
        newProductItem.classList.add('product-item');
        newProductItem.innerHTML = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="product_id_${productCounter}">Product</label>
                    <select class="form-select product-select" id="product_id_${productCounter}" name="products[${productCounter}][product_id]" required>
                        <option value="">Select Product</option>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku'] ?? '') ?>)</option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No products available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantity_${productCounter}">Quantity</label>
                    <input type="number" class="form-control" id="quantity_${productCounter}" name="products[${productCounter}][quantity]" value="1" min="1" required>
                </div>
                <div class="col-md-3">
                    <label for="unit_price_${productCounter}">Unit Price</label>
                    <input type="number" class="form-control unit-price-input" id="unit_price_${productCounter}" name="products[${productCounter}][unit_price]" step="0.01" required>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-product-row">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        productList.appendChild(newProductItem);
        productCounter++;
        attachEventListenersToNewRow(newProductItem); // Attach listeners to the new row
    });

    function attachEventListenersToNewRow(row) {
        const productSelect = row.querySelector('.product-select');
        const unitPriceInput = row.querySelector('.unit-price-input');
        const quantityInput = row.querySelector('[name$="[quantity]"]');

        if (productSelect && unitPriceInput && quantityInput) {
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.price) {
                    unitPriceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
                    calculateTotal();
                } else {
                    unitPriceInput.value = ''; // Clear price if no product selected
                    calculateTotal();
                }
            });
            quantityInput.addEventListener('input', calculateTotal);
            unitPriceInput.addEventListener('input', calculateTotal);
            row.querySelector('.remove-product-row').addEventListener('click', function() {
                this.parentNode.remove();
                calculateTotal();
            });
        }
    }

    // Attach event listener to the initial product row
    attachEventListenersToNewRow(document.querySelector('.product-item'));

    function calculateTotal() {
        let total = 0;
        const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            const quantityInput = item.querySelector('[name$="[quantity]"]');
            const unitPriceInput = item.querySelector('[name$="[unit_price]"]');
            if (quantityInput && unitPriceInput) {
                total += parseFloat(quantityInput.value) * parseFloat(unitPriceInput.value);
            }
        });
        totalAmountInput.value = total.toFixed(2);
    }

    addPurchaseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('process_purchase.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = 'purchase.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    });
</script>
</body>
</html>