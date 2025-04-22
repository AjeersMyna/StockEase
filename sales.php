<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
require_once 'models/Sale.php';
require_once 'models/Customer.php';
require_once 'models/Product.php';

$saleModel = new Sale($conn);
$customerModel = new Customer($conn);
$productModel = new Product($conn);

$sales = $saleModel->getSales();
$customers = $customerModel->getCustomers();
$products = $productModel->getProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fas fa-shopping-cart me-2"></i>Sales</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSaleModal">
                <i class="fas fa-plus"></i> New Sale
            </button>
        </div>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= htmlspecialchars($sale['invoice_number']) ?></td>
                    <td><?= date('d M Y', strtotime($sale['sale_date'])) ?></td>
                    <td><?= htmlspecialchars($sale['customer_name'] ?? 'Walk-in') ?></td>
                    <td>₹<?= number_format($sale['total_amount'], 2) ?></td>
                    <td>
                        <?php if (isset($sale['status'])): ?>
                            <span class="badge bg-<?=
                                $sale['status'] == 'completed' ? 'success' :
                                ($sale['status'] == 'pending' ? 'warning' : 'danger')
                            ?>">
                                <?= ucfirst($sale['status']) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Unknown</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="view_sale.php?id=<?= $sale['id'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="modal fade" id="newSaleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Sale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="saleForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" name="invoice_number" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Customer</label>
                                    <select class="form-select" name="customer_id">
                                        <option value="">Walk-in Customer</option>
                                        <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0">Products</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <select class="form-select" id="productSelect">
                                                <option value="">Select Product</option>
                                                <?php
                                                foreach ($products as $product):
                                                ?>
                                                <option value="<?= $product['id'] ?>"
                                                    data-price="<?= $product['price'] ?>"
                                                    data-stock="<?= $product['stock_quantity'] ?>">
                                                    <?= htmlspecialchars($product['name']) ?> (₹<?= $product['price'] ?>)
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" id="productQty" placeholder="Qty" min="1" value="1">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary w-100" id="addProductBtn">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>

                                    <table class="table table-sm" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="table-active">
                                                <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                                <td id="grandTotal">₹0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save Sale</button>
                            </div>
                        </form>
                                            </div>
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-primary">Save Sale</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Product Management
const productTable = document.getElementById('productTable').querySelector('tbody');
const grandTotalEl = document.getElementById('grandTotal');
let products = [];

document.getElementById('addProductBtn').addEventListener('click', function() {
    const productSelect = document.getElementById('productSelect');
    const productQty = document.getElementById('productQty');

    if (!productSelect.value || !productQty.value || productQty.value < 1) {
        alert('Please select a product and valid quantity');
        return;
    }

    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text.split(' (₹')[0];
    const price = parseFloat(productSelect.dataset.price);
    const quantity = parseInt(productQty.value);

    // Add to array
    products.push({
        id: productId,
        name: productName,
        price: price,
        quantity: quantity
    });

    // Update UI
    renderProductTable();
    productQty.value = 1;
});

function renderProductTable() {
    productTable.innerHTML = '';
    let grandTotal = 0;

    products.forEach((product, index) => {
        const total = product.price * product.quantity;
        grandTotal += total;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.name}</td>
            <td>₹${product.price.toFixed(2)}</td>
            <td>${product.quantity}</td>
            <td>₹${total.toFixed(2)}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-danger remove-product" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        productTable.appendChild(row);
    });

    grandTotalEl.textContent = `₹${grandTotal.toFixed(2)}`;
}

// Remove product
productTable.addEventListener('click', function(e) {
    if (e.target.closest('.remove-product')) {
        const index = e.target.closest('.remove-product').dataset.index;
        products.splice(index, 1);
        renderProductTable();
    }
});

// Form Submission
document.getElementById('saleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (products.length === 0) {
        alert('Please add at least one product');
        return;
    }

    const formData = {
        invoice_number: this.invoice_number.value,
        customer_id: this.customer_id.value || null,
        total_amount: parseFloat(grandTotalEl.textContent.replace('₹', '')),
        products: products
    };

    fetch('api/add_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sale saved successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to save sale'));
        }
    });
});
</script>
</body>
</html>