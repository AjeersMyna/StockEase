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

// Fetch suppliers for the dropdown
try {
    $stmt = $conn->prepare("SELECT id, name FROM suppliers ORDER BY name ASC");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching suppliers: " . $e->getMessage();
    $suppliers = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Add Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F5F5DC;
            font-family: 'Inter', sans-serif;
        }

        h2 {
            color: #2c3e50;
            font-weight: 700;
        }

        h5 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        .btn-success {
            background-color: #8FBC8F !important;
            color: #FFFFFF !important;
            border-color: #8FBC8F !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #66CDAA !important;
            color: #FFFFFF !important;
            border-color: #66CDAA !important;
        }

        .btn-secondary {
            background-color: #ADD8E6 !important;
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #90CAF9 !important;
            color: #2c3e50 !important;
            border-color: #90CAF9 !important;
        }

        .card {
            border: 1px solid #E0EEE0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #F8F8FF;
            border-bottom-color: #E0EEE0;
            padding: 1.25rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #F8F8FF;
        }

        .table-striped td,
        .table-striped th {
            border-bottom-color: #E0EEE0;
        }

        .table-striped thead th {
            border-bottom-width: 2px;
            border-bottom-color: #ADD8E6;
            color: #2c3e50;
            font-weight: 600;
        }

        .table-active {
            background-color: #F0F8FF !important;
            font-weight: bold;
            color: #2c3e50;
        }

        .badge-success {
            background-color: #C1E1C1 !important;
            color: #2E8B57 !important;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }

        .badge-warning {
            background-color: #FFFACD !important;
            color: #8B4513 !important;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        .form-control,
        .form-select {
            border: 1px solid #ADD8E6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #8FBC8F;
            box-shadow: 0 0 0 3px rgba(143, 188, 143, 0.15);
        }

        #product-list {
            margin-bottom: 20px;
        }

        .product-item {
            border: 1px solid #E0EEE0;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 0.5rem;
            background-color: #F8F8FF;
        }

        .remove-product-row {
            background-color: #DC143C;
            color: #FFFFFF;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .remove-product-row:hover {
            background-color: #B22222;
        }

        #addProductRow {
            background-color: #8FBC8F;
            color: #FFFFFF;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #addProductRow:hover {
            background-color: #66CDAA;
        }

        #total_amount {
            background-color: #F0F8FF;
            border: 1px solid #ADD8E6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            box-sizing: border-box;
            font-weight: bold;
            color: #2c3e50;
        }

        button[type="submit"] {
            background-color: #8FBC8F;
            color: #FFFFFF;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #66CDAA;
        }

        .alert {
            margin-top: 20px;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .alert-success {
            background-color: #C1E1C1;
            color: #2E8B57;
            border-color: #2E8B57;
        }

        .alert-danger {
            background-color: #FFFACD;
            color: #8B4513;
            border-color: #8B4513;
        }

        .back-button {
            margin-bottom: 20px;
        }

        .back-button a {
            background-color: #ADD8E6;
            color: #2c3e50;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-button a:hover {
            background-color: #90CAF9;
        }
    </style>
</head>
<body>
<div class="container-fluid p-4">
    <div class="back-button">
        <a href="purchase.php"><i class="fas fa-arrow-left me-2"></i>Back to Purchases</a>
    </div>
    <h2><i class="fas fa-plus me-2"></i>Add New Purchase</h2>

    <form id="addPurchaseForm">
        <div class="mb-3">
            <label for="supplier_id">Supplier</label>
            <select class="form-select" id="supplier_id" name="supplier" required>
                <option value="">Select Supplier</option>
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No suppliers available</option>
                <?php endif; ?>
            </select>
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
                                    <option value="<?= $product['id'] ?>"
                                            data-price="<?= $product['price'] ?>"><?= htmlspecialchars($product['name']) ?>
                                        (<?= htmlspecialchars($product['sku'] ?? '') ?>)</option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No products available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity_0">Quantity</label>
                        <input type="number" class="form-control quantity-input" id="quantity_0" name="products[0][quantity]" value="1"
                               min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="unit_price_0">Unit Price</label>
                        <input type="number" class="form-control unit-price-input" id="unit_price_0"
                               name="products[0][unit_price]" step="0.01" required readonly>
                    </div>
                     <div class="col-md-3">
                        <label for="total_price_0">Total Price</label>
                        <input type="number" class="form-control total-price-input" id="total_price_0"
                               name="products[0][total_price]" step="0.01" required readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-danger btn-sm remove-product-row">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success mb-3" id="addProductRow">
            <i class="fas fa-plus"></i> Add Product
        </button>

        <div class="mb-3">
            <label for="total_amount">Total Amount</label>
            <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Purchase</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let productCount = 1;

        function calculateTotal(row) {
            let quantity = row.find('.quantity-input').val();
            let unitPrice = row.find('.unit-price-input').val();
            let totalPrice = quantity * unitPrice;
            row.find('.total-price-input').val(totalPrice.toFixed(2));
            return totalPrice;
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.product-item').each(function () {
                grandTotal += parseFloat($(this).find('.total-price-input').val()) || 0;
            });
            $('#total_amount').val(grandTotal.toFixed(2));
        }

        // Initial calculation for the first product row
        calculateTotal($('.product-item:first'));
        calculateGrandTotal();

        $(document).on('change', '.product-select', function () {
            let selectedProductId = $(this).val();
            let price = $(this).find(':selected').data('price');
            let row = $(this).closest('.product-item');
            row.find('.unit-price-input').val(price);
            calculateTotal(row);
            calculateGrandTotal();
        });

        $(document).on('input', '.quantity-input', function () {
            let row = $(this).closest('.product-item');
            calculateTotal(row);
            calculateGrandTotal();
        });

        $('#addProductRow').click(function () {
            let newProductRow = $('.product-item:first').clone();
            newProductRow.find('select').attr('id', 'product_id_' + productCount).attr('name', 'products[' + productCount + '][product_id]').val('');
            newProductRow.find('input[type="number"]').attr('id', 'quantity_' + productCount).attr('name', 'products[' + productCount + '][quantity]').val(1);
            newProductRow.find('.unit-price-input').attr('id', 'unit_price_' + productCount).attr('name', 'products[' + productCount + '][unit_price]').val('');
            newProductRow.find('.total-price-input').attr('id', 'total_price_' + productCount).attr('name', 'products[' + productCount + '][total_price]').val('');
            newProductRow.find('.remove-product-row').show();
            $('#product-list').append(newProductRow);

            // Re-initialize the change event listener for the new product select
            newProductRow.find('.product-select').on('change', function() {
                let selectedProductId = $(this).val();
                let price = $(this).find(':selected').data('price');
                let row = $(this).closest('.product-item');
                row.find('.unit-price-input').val(price);
                calculateTotal(row);
                calculateGrandTotal();
            });

            productCount++;
        });

        $(document).on('click', '.remove-product-row', function () {
            $(this).closest('.product-item').remove();
            calculateGrandTotal();
        });

        $('#addPurchaseForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: 'process_purchase.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = 'purchase.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("An error occurred while processing your request.");
                }
            });
        });
    });
</script>