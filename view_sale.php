<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

class SaleModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        // Check if the database connection is valid
        if (!$this->conn) {
            throw new Exception("Failed to connect to the database.");
        }
    }

    public function getSaleDetails($saleId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, c.name AS customer_name
                FROM sales s
                LEFT JOIN customers_1 c ON s.customer_id = c.id
                WHERE s.id = :id
            ");
            $stmt->bindParam(':id', $saleId, PDO::PARAM_INT); // Use PDO::PARAM_INT
            $stmt->execute();
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);
             if (!$sale) {
                return null; // Return null if sale not found
             }
            return $sale;
        } catch (PDOException $e) {
            // Log the error to a file or database
            error_log("Error in getSaleDetails: " . $e->getMessage(), 0);
            throw new Exception("Error fetching sale details: " . $e->getMessage()); //convert to Exception
        }
    }

    public function getSaleItems($saleId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT si.*, p.name AS product_name
                FROM sale_items si
                LEFT JOIN products p ON si.product_id = p.id
                WHERE si.sale_id = :sale_id
            ");
            $stmt->bindParam(':sale_id', $saleId, PDO::PARAM_INT);  // Use PDO::PARAM_INT
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error
            error_log("Error in getSaleItems: " . $e->getMessage(), 0);
            throw new Exception("Error fetching sale items: " . $e->getMessage());
        }
    }

    public function getCustomerNameById($customerId) {
         try {
            if ($customerId) {
                $stmt = $this->conn->prepare("SELECT name FROM customers_1 WHERE id = :id");
                $stmt->bindParam(':id', $customerId, PDO::PARAM_INT); // Use PDO::PARAM_INT
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ? $result['name'] : 'Unknown Customer';
            }
            return 'Walk-in';
          } catch (PDOException $e) {
            error_log("Error in getCustomerNameById: " . $e->getMessage(), 0);
            throw new Exception("Error fetching customer name: " . $e->getMessage());
          }
    }
}

try { // Added try block here
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $saleId = $_GET['id'];
        $saleModel = new SaleModel($conn); //connection is already checked in constructor.
        $sale = $saleModel->getSaleDetails($saleId);
        $saleItems = $saleModel->getSaleItems($saleId);

        if (!$sale) {
            // Handle the error: Sale not found
            echo "<div class='alert alert-danger'>Sale not found.</div>";
            exit();
        }
        $customerName = $sale['customer_name'] ?? $saleModel->getCustomerNameById($sale['customer_id']);

    } else {
        // Handle the error: Invalid sale ID
        echo "<div class='alert alert-danger'>Invalid sale ID.</div>";
        exit();
    }
} catch (Exception $e) {  // Catch any exceptions thrown, including PDOExceptions
    echo "<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit(); // IMPORTANT: Stop execution to prevent further errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sale Details - StockEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .container-fluid {
            padding-left: 20px;
            padding-right: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-bottom: 1px solid #007bff;
        }
        .card-body {
            padding: 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #e9ecef;
            border-bottom: 2px solid #ddd;
            font-weight: 600;
        }
        .table td, .table th {
            border-top: 1px solid #ddd;
            vertical-align: middle;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include('views/partials/sidebar.php'); ?>

        <div class="container-fluid p-4">
            <h2>Sale Details</h2>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-invoice me-2"></i>Sale Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Sale ID:</strong> <?= htmlspecialchars($sale['id']) ?></p>
                            <p><strong>Invoice Number:</strong> <?= htmlspecialchars($sale['invoice_number']) ?></p>
                            <p><strong>Customer:</strong> <?= htmlspecialchars($customerName) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sale Date:</strong> <?= date('d M Y h:i:s A', strtotime($sale['sale_date'])) ?></p>
                            <p><strong>Total Amount:</strong> ₹<?= number_format($sale['total_amount'], 2) ?></p>
                        </div>
                    </div>
                    <a href="sales.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left me-2"></i>Back to Sales</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-box me-2"></i>Products
                </div>
                <div class="card-body">
                    <?php if (empty($saleItems)): ?>
                        <p>No products found for this sale.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($saleItems as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                                            <td>₹<?= number_format($item['unit_price'], 2) ?></td>
                                            <td>₹<?= number_format($item['quantity'] * $item['unit_price'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
