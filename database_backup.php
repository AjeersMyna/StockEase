<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'backup') {
        // Get the database credentials from the db.php file
        $host = "localhost";
        $username = "root";
        $password = "";
        $databaseName = "STOCKEASE_CUSTOMERS";

        // Build the command to execute mysqldump
        $command = "mysqldump --host=$host --user=$username --password=$password $databaseName";
        // Add more options
        $command .= " --result-file=backup.sql";

        // Execute the command
        system($command, $return_var);

        if ($return_var == 0) {
            header("Location: database_backup.php?success=1");
            exit();
        } else {
            header("Location: database_backup.php?error=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Database Backup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-database me-2"></i>Database Backup</h2>
        <hr class="mb-4">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Database backed up successfully!  The backup is saved as backup.sql
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error backing up database!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="action" value="backup">
            <button type="submit" class="btn btn-warning">Backup Database</button>
        </form>

        <a href="system.php" class="btn btn-secondary mt-3">Back to System Settings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
