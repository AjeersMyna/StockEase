<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
// You might need to include a Purchase model here later
// require_once 'models/Purchase.php';
// $purchaseModel = new Purchase($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>