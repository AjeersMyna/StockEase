<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
// You might need an Inventory model later
// require_once 'models/Inventory.php';
// $inventoryModel = new Inventory($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #F5F5DC; /* Seashell */
        }
        h2 {
            color: #2c3e50; /* Darker, more professional heading */
            font-weight: 700;
        }
        .btn-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            border-color: #90CAF9 !important;
        }
        .alert {
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        }
        .alert-success {
            background-color: #F0FFF0 !important; /* Honeydew */
            color: #2c3e50 !important;
            border-color: #F0FFF0 !important;
        }
        .alert-danger {
            background-color: #F08080 !important; /* Light Coral */
            color: #fff !important;
            border-color: #F08080 !important;
        }
        .btn-close {
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fas fa-boxes me-2"></i>Inventory</h2>
            <a href="add_inventory.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Inventory
            </a>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Inventory updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: <?php
                if ($_GET['error'] === 'productnotfoundininventory') {
                    echo 'Product not found in inventory.';
                } else {
                    echo htmlspecialchars($_GET['error']);
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5><i class="fas fa-list me-2"></i>Current Inventory</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Reorder Level</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT id, sku, name, quantity, reorder_level, price FROM inventory");
                                $stmt->execute();
                                $inventoryData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($inventoryData) {
                                    foreach ($inventoryData as $row) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['sku']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['reorder_level']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No inventory data available.</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6'>Error fetching inventory data: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>