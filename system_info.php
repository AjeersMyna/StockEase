<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database configuration file
require_once 'db.php';

// Function to get server information
function getServerInfo() {
    $serverInfo = array();
    $serverInfo['php_version'] = phpversion();
    $serverInfo['server_software'] = $_SERVER['SERVER_SOFTWARE'];
    $serverInfo['os'] = php_uname('s') . ' ' . php_uname('r');
    $serverInfo['document_root'] = $_SERVER['DOCUMENT_ROOT'];
    return $serverInfo;
}

// Function to get database information
function getDatabaseInfo($conn, $db_host, $db_name) {
    $databaseInfo = array();
    try {
        //check if the connection is valid
        if ($conn){
            $databaseInfo['connection_status'] = 'Connected';
        }
        else{
            $databaseInfo['connection_status'] = 'Not Connected';
        }

        $databaseInfo['host'] = "root";
        $databaseInfo['name'] = "STOCKEASE_CUSTOMERS";
        return $databaseInfo;
    } catch (Exception $e) {
        $databaseInfo['connection_status'] = 'Error: ' . $e->getMessage();
        $databaseInfo['host'] = 'N/A';
        $databaseInfo['name'] = 'N/A';
        return $databaseInfo;
    }
}

// Get server information
$server = getServerInfo();

// Get database information
// Assuming $conn is already established in db.php
$database = getDatabaseInfo($conn, $db_host, $db_name);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - System Information</title>
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

        h3 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .btn-secondary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            color: #2c3e50 !important;
        }

        .card {
            border: 1px solid #E0EEE0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #F8F8FF; /* Very light background for card header */
            border-bottom-color: #E0EEE0;
            padding: 1.25rem;
            border-radius: 0.5rem 0.5rem 0 0;
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

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #F8F8FF; /* Very light background for striped rows */
        }

        .table-striped td,
        .table-striped th {
            border-bottom-color: #E0EEE0;
        }

        .table-striped thead th {
            border-bottom-width: 2px;
            border-bottom-color: #ADD8E6;
            color: #2c3e50;
            font-weight: 600;
        }

        .table-bordered {
            border-color: #E0EEE0;
        }

        .table-bordered td,
        .table-bordered th {
            border-color: #E0EEE0;
        }

        .table-bordered thead th {
            border-bottom-width: 2px;
            border-bottom-color: #ADD8E6;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-info-circle me-2"></i>Server Information</h2>
        <hr class="mb-4">

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Server Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th>PHP Version</th>
                                        <td><?php echo $server['php_version']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Server Software</th>
                                        <td><?php echo $server['server_software']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Operating System</th>
                                        <td><?php echo $server['os']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Document Root</th>
                                        <td><?php echo $server['document_root']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Database Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Database Connection</th>
                                        <td><?php echo $database['connection_status']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Database Host</th>
                                        <td><?php echo $database['host']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Database Name</th>
                                        <td><?php echo $database['name']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="system.php" class="btn btn-secondary mt-3">Back to System Settings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
