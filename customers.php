<?php
include 'db.php';
$result = $conn->query("SELECT * FROM real_customers");
$customers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100" style="width: 250px;">
        <h4 class="text-center">STOCKPRO</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Sales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Purchase</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Inventory</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">System</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid p-4">
        <h2>Manage Customers</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <label>Show 
                    <select class="form-select d-inline" style="width: auto;">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                    </select> entries
                </label>
            </div>
            <div>
                <input type="text" class="form-control" placeholder="Search..." id="search">
            </div>
            <a href="add.php" class="btn btn-primary">Add Customer</a>
        </div>

        <!-- Customer Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Contact Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Xero</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= $customer['name'] ?></td>
                        <td><?= $customer['contact_name'] ?></td>
                        <td><?= $customer['phone'] ?></td>
                        <td><?= $customer['email'] ?></td>
                        <td><?= $customer['country'] ?></td>
                        <td><?= $customer['xero_account'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $customer['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                            <a href="delete.php?id=<?= $customer['id'] ?>" class="btn btn-danger btn-sm">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

</body>
</html>
