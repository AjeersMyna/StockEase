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
        body {
            background-color: #F5F5DC; /* Seashell */
        }
        .card {
            transition: transform 0.3s ease-in-out;
            border: 1px solid #E0EEE0; /* Light Honeydew */
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }
        .card:hover {
            transform: translateY(-0.5rem) scale(1.02);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        }
        .bg-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important; /* Darker, more professional text */
        }
        .bg-success {
            background-color: #F0FFF0 !important; /* Honeydew */
            color: #2c3e50 !important;
        }
        .bg-warning {
            background-color: #FFFACD !important; /* Light Yellowish */
            color: #2c3e50 !important;
        }
        .bg-info {
            background-color: #E0FFFF !important; /* Light Cyan */
            color: #2c3e50 !important;
        }
        .card-header {
            background-color: #E0EEE0 !important; /* Honeydew */
            color: #2c3e50 !important; /* Darker, more professional text */
            border-bottom: 1px solid #ADD8E6; /* Powder Blue accent */
            padding: 0.75rem 1.25rem;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 500; /* Medium font weight */
        }
        .display-6 {
            font-size: 2.5rem;
            font-weight: 600; /* Stronger font weight for numbers */
        }
        h2 {
            color: #2c3e50; /* Darker, more professional heading color */
            font-weight: 700;  /* Make heading bold */
        }
        .container-fluid {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .mb-4 {
            margin-bottom: 2.5rem !important;
        }
        .card-body {
            padding: 1.25rem;
        }
        .chart-container {
            border: 1px solid #E0EEE0;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title" style="color: #2c3e50 !important;">Today's Sales</h5>
                            <i class="fas fa-rupee-sign fa-2x" style="color: #2c3e50 !important;"></i>
                        </div>
                        <p class="card-text display-6 mt-3" style="color: #2c3e50 !important;">₹<?= number_format($dashboardModel->getTodaysSales(), 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title" style="color: #2c3e50 !important;">Monthly Revenue</h5>
                            <i class="fas fa-chart-line fa-2x" style="color: #2c3e50 !important;"></i>
                        </div>
                        <p class="card-text display-6 mt-3" style="color: #2c3e50 !important;">₹<?= number_format($dashboardModel->getMonthlyRevenue(), 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title" style="color: #2c3e50 !important;">Low Stock Items</h5>
                            <i class="fas fa-exclamation-triangle fa-2x" style="color: #2c3e50 !important;"></i>
                        </div>
                        <p class="card-text display-6 mt-3" style="color: #2c3e50 !important;"><?= $dashboardModel->getLowStockCount() ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title" style="color: #2c3e50 !important;">Active Customers</h5>
                            <i class="fas fa-users fa-2x" style="color: #2c3e50 !important;"></i>
                        </div>
                        <p class="card-text display-6 mt-3" style="color: #2c3e50 !important;"><?= $dashboardModel->getActiveCustomerCount() ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Monthly Sales Trend</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart" height="320"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-boxes me-2"></i>Inventory Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="inventoryChart" height="320"></canvas>
                        </div>
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
                borderColor: '#87CEFA', // Powder Blue
                backgroundColor: 'rgba(173, 216, 230, 0.2)', // Light Powder Blue
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
                    'rgba(144, 238, 144, 0.8)', // Light Green (Honeydew)
                    'rgba(255, 228, 181, 0.8)', // Light Orange (Seashell-ish)
                    'rgba(240, 128, 128, 0.8)'  // Light Red
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
            },
            // Add padding to the chart
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
                }
            }
        }
    });
});
</script>
</body>
</html>
