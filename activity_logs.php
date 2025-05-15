<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';
$logFilePath = 'activity.log';  //  log file.

$logs = [];
if (file_exists($logFilePath)) {
    $file = fopen($logFilePath, 'r');
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $logs[] = $line;
        }
        fclose($file);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Activity Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-history me-2"></i>Activity Logs</h2>
        <hr class="mb-4">

        <?php if (empty($logs)): ?>
            <div class="alert alert-info" role="alert">
                No activity logs found.
            </div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($logs as $log): ?>
                    <li class="list-group-item"><?= htmlspecialchars($log) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="system.php" class="btn btn-secondary mt-3">Back to System Settings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>