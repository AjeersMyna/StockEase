<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
// You might include system-related functions or models later
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-cog me-2"></i>System Settings</h2>
        <hr class="mb-4">

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i>User Management
                    </div>
                    <div class="card-body">
                        <p>Manage user accounts, roles, and permissions.</p>
                        <a href="manage_users.php" class="btn btn-primary btn-sm">Manage Users</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-cogs me-2"></i>Application Settings
                    </div>
                    <div class="card-body">
                        <p>Configure general application settings such as currency, date format, etc.</p>
                        <a href="#" class="btn btn-primary btn-sm">View Settings</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-database me-2"></i>Database Management
                    </div>
                    <div class="card-body">
                        <p>Tools for managing the application database, such as backup and restore.</p>
                        <a href="#" class="btn btn-warning btn-sm">Backup Database</a>
                        <a href="#" class="btn btn-info btn-sm">Restore Database</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </div>
                    <div class="card-body">
                        <p>View information about the server environment and application.</p>
                        <ul>
                            <li>PHP Version: <?php echo phpversion(); ?></li>
                            <li>Database Connection: <?php echo (isset($conn) ? '<span class="text-success">Connected</span>' : '<span class="text-danger">Not Connected</span>'); ?></li>
                        </ul>
                        <a href="#" class="btn btn-secondary btn-sm">More Info</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>Activity Logs
                </div>
                <div class="card-body">
                    <p>View a log of important activities within the application.</p>
                    <a href="#" class="btn btn-secondary btn-sm">View Logs</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>