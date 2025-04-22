<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
require_once 'models/dashboard.php';

$dashboardModel = new Dashboard($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card { transition: transform 0.2s; }
        .card:hover { transform: scale(1.02); }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Today's Sales</h5>
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                        <p class="card-text display-6 mt-3">₹<?= number_format($dashboardModel->getTodaysSales(), 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Monthly Revenue</h5>
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <p class="card-text display-6 mt-3">₹<?= number_format($dashboardModel->getMonthlyRevenue(), 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Low Stock Items</h5>
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <p class="card-text display-6 mt-3"><?= $dashboardModel->getLowStockCount() ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Active Customers</h5>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <p class="card-text display-6 mt-3"><?= $dashboardModel->getActiveCustomerCount() ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h5><i class="fas fa-chart-bar me-2"></i>Monthly Sales Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h5><i class="fas fa-boxes me-2"></i>Inventory Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="inventoryChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize Charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Sales Line Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($dashboardModel->getLast6Months()) ?>,
            datasets: [{
                label: 'Sales (₹)',
                data: <?= json_encode($dashboardModel->getMonthlySalesData()) ?>,
                borderColor: '#4bc0c0',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Inventory Doughnut Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: <?= json_encode($dashboardModel->getInventoryStatusData()) ?>,
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' items';
                        }
                    }
                }
            }
        }
    });
});
</script>
</body>
</html>