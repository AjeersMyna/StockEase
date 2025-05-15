<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// Fetch purchases from the database, ordered by purchase_date
try {
    $stmt = $conn->prepare("
        SELECT
            id,
            supplier,
            purchase_date,
            total_amount,
            notes
        FROM purchases
        ORDER BY purchase_date DESC
    ");
    $stmt->execute();
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $purchases = []; // Ensure $purchases is always defined to avoid errors later
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FFF5EE;
            font-family: 'Inter', sans-serif;
        }

        h2 {
            color: #2d3748;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #B0E0E6 !important;
            color: #2d3748 !important;
            border-color: #B0E0E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #87CEFA !important;
            color: #2d3748 !important;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
            border-radius: 1rem;
        }

        .badge-warning {
            background-color: #f0ad4e;
            color: #ffffff;
        }

        .badge-success {
            background-color: #5cb85c;
            color: #ffffff;
        }

        .badge-danger {
            background-color: #d9534f;
            color: #ffffff;
        }

        .pagination {
            margin-top: 20px;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .page-link {
            color: #007bff;
        }

        .page-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fas fa-truck-loading me-2"></i>Purchases</h2>
            <a href="add_purchase.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Purchase
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Supplier ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($purchases)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No purchases found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <td><?= $purchase['id'] ?></td>
                                <td><?= $purchase['supplier'] ?></td>
                                <td><?= $purchase['purchase_date'] ?></td>
                                <td>$<?= number_format($purchase['total_amount'], 2) ?></td>
                                <td><?= htmlspecialchars($purchase['notes']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
