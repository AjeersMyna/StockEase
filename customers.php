<?php
require_once 'db.php';
require_once 'models/Customer.php';

$customerModel = new Customer($conn);

// Get search and pagination parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default to 10 entries per page
$offset = ($page - 1) * $limit;

// Fetch customers with pagination
$customers = $customerModel->getCustomers($search, $limit, $offset);
$totalCustomers = $customerModel->getTotalCustomers($search); // Get total count for pagination
$totalPages = ceil($totalCustomers / $limit);
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

<div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100" style="width: 250px;">
        <h4 class="text-center">STOCKEASE</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Sales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Purchase</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Inventory</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">System</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Logout</a></li>
        </ul>
    </nav>

    <div class="container-fluid p-4">
        <h2>Manage Customers</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <label>Show 
                    <select id="entriesPerPage" class="form-select d-inline" style="width: auto;">
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                    </select> entries
                </label>
            </div>
            <div>
                <input type="text" class="form-control" placeholder="Search..." id="search" value="<?= htmlspecialchars($search) ?>">
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
                        <td><?= htmlspecialchars($customer['id']) ?></td>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['contact_name']) ?></td>
                        <td><?= htmlspecialchars($customer['phone']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['country']) ?></td>
                        <td><?= htmlspecialchars($customer['xero_account']) ?></td>
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
                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#entriesPerPage").on("change", function() {
            let selectedLimit = $(this).val();
            let searchValue = $("#search").val();
            window.location.href = "customers.php?search=" + encodeURIComponent(searchValue) + "&limit=" + selectedLimit + "&page=1";
        });

        $("#search").on("keypress", function(e) {
            if (e.which === 13) {
                let searchValue = $(this).val();
                let selectedLimit = $("#entriesPerPage").val();
                window.location.href = "customers.php?search=" + encodeURIComponent(searchValue) + "&limit=" + selectedLimit + "&page=1";
            }
        });
    });
</script>

</body>
</html>
