<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'restore') {
        $host = "localhost";
        $username = "root";
        $password = "";
        $databaseName = "STOCKEASE_CUSTOMERS";

        $restoreFile = $_FILES['restore_file']['tmp_name'];

        if (!$restoreFile) {
            header("Location: database_restore.php?error=2");
            exit();
        }
        $command = "mysql --host=$host --user=$username --password=$password $databaseName < $restoreFile";

        system($command, $return_var);

        if ($return_var == 0) {
            header("Location: database_restore.php?success=1");
            exit();
        } else {
            header("Location: database_restore.php?error=1");
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
    <title>StockEase - Database Restore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-database me-2"></i>Database Restore</h2>
        <hr class="mb-4">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Database restored successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error restoring database!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: Please select a file to restore!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="restore">
            <div class="mb-3">
                <label for="restore_file">Restore File (.sql)</label>
                <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".sql" required>
            </div>
            <button type="submit" class="btn btn-info">Restore Database</button>
        </form>

        <a href="system.php" class="btn btn-secondary mt-3">Back to System Settings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
