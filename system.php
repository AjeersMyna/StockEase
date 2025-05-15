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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F5F5DC; /* Seashell */
            font-family: 'Inter', sans-serif;
        }

        h2 {
            color: #2c3e50; /* Darker, more professional heading */
            font-weight: 700;
        }

        .card {
            border: 1px solid #E0EEE0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: #F8F8FF; /* Very light background for card header */
            border-bottom-color: #E0EEE0;
            padding: 1.25rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .card-header i {
            margin-right: 0.75rem;
            color: #4a5568; /* Darker icon color */
        }

        .card-title {
            color: #2c3e50;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-body p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            color: #2c3e50 !important;
            border-color: #90CAF9 !important;
        }

        .btn-warning {
            background-color: #FFE4B5 !important; /* Light Orange */
            color: #2c3e50 !important;
            border-color: #FFE4B5 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #FFDAB9 !important; /* Lighter orange on hover */
            color: #2c3e50 !important;
            border-color: #FFDAB9 !important;
        }

        .btn-info {
            background-color: #87CEFA !important; /* Light Blue */
            color: #2c3e50 !important;
            border-color: #87CEFA !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-info:hover {
            background-color: #ADD8E6 !important; /* Lighter blue on hover */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
        }

        .btn-secondary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            color: #2c3e50 !important;
            border-color: #90CAF9 !important;
        }

        .text-success {
            color: #2E8B57 !important; /* Darker green for success */
        }

        .text-danger {
            color: #CD5C5C !important; /* Darker red for danger */
        }

        .list-unstyled {
            padding-left: 0;
            margin-bottom: 0;
        }

        .list-unstyled li {
            margin-bottom: 0.5rem;
            color: #4a5568;
        }

        .list-unstyled li strong {
            color: #2c3e50;
        }
    </style>
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
                        <a href="application_settings.php" class="btn btn-primary btn-sm">View Settings</a>
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
                        <a href="database_backup.php" class="btn btn-warning btn-sm">Backup Database</a>
                        <a href="database_restore.php" class="btn btn-info btn-sm">Restore Database</a>
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
                        <ul class="list-unstyled">
                            <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
                            <li><strong>Database Connection:</strong> <?php echo (isset($conn) ? '<span class="text-success">Connected</span>' : '<span class="text-danger">Not Connected</span>'); ?></li>
                        </ul>
                        <a href="system_info.php" class="btn btn-secondary btn-sm">More Info</a>
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
                    <a href="activity_logs.php" class="btn btn-secondary btn-sm">View Logs</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
