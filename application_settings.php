<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

//  Fetch application settings from the database.
try {
    $stmt = $conn->prepare("SELECT * FROM settings"); //  settings table
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$settings) {
         $settings = [  //default values
            'app_name' => 'StockEase',
            'currency' => '$',
            'date_format' => 'Y-m-d',
        ];
    }
} catch (PDOException $e) {
    echo "Error fetching settings: " . $e->getMessage();
    $settings = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $appName = $_POST['app_name'];
        $currency = $_POST['currency'];
        $dateFormat = $_POST['date_format'];

         $stmt = $conn->prepare("UPDATE settings SET app_name = ?, currency = ?, date_format = ?");
         $stmt->execute([$appName, $currency, $dateFormat]);
        header("Location: application_settings.php?success=1");
        exit();

    } catch (PDOException $e) {
        echo "Error updating settings: " . $e->getMessage();
        header("Location: application_settings.php?error=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Application Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-cogs me-2"></i>Application Settings</h2>
        <hr class="mb-4">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Settings updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error updating settings!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="app_name">Application Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name" value="<?php echo htmlspecialchars($settings['app_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="currency">Currency</label>
                <input type="text" class="form-control" id="currency" name="currency" value="<?php echo htmlspecialchars($settings['currency']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_format">Date Format</label>
                <input type="text" class="form-control" id="date_format" name="date_format" value="<?php echo htmlspecialchars($settings['date_format']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Settings</button>
        </form>
        <a href="system.php" class="btn btn-secondary mt-3">Back to System Settings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```