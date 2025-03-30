<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

require_once 'db.php';
require_once 'models/Customer.php';

$customerModel = new Customer($conn);

// Get search and pagination parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// Fetch customers and total count
$customers = $customerModel->getCustomers($search, $limit, $offset);
$totalCustomers = $customerModel->getTotalCustomers($search);
$totalPages = max(1, ceil($totalCustomers / $limit));
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
            <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
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
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $customer) : ?>
            <tr data-id="<?= htmlspecialchars($customer['id']) ?>">
                <td><?= htmlspecialchars($customer['id']) ?></td>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['contact_name']) ?></td>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td><?= htmlspecialchars($customer['country']) ?></td>
                <td><?= htmlspecialchars($customer['xero_account']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($customer['id']) ?>" class="btn btn-warning btn-sm">✏️</a>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= htmlspecialchars($customer['id']) ?>">🗑️</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= max(1, $page - 1) ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= min($totalPages, $page + 1) ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="deleteToast" class="toast bg-success text-white" role="alert">
        <div class="toast-body"></div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Delete customer on button click
    $(document).on("click", ".delete-btn", function () {
        let customerId = $(this).data("id");

        if (!confirm("Are you sure you want to delete this customer?")) return;

        $.ajax({
            url: "delete.php", // Ensure this matches your API endpoint
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ id: customerId }),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Remove row from table
                    $(`tr[data-id="${customerId}"]`).fadeOut(300, function () {
                        $(this).remove();
                    });

                    showToast("Customer deleted successfully!", "success");
                } else {
                    showToast(response.message, "danger");
                }
            },
            error: function () {
                showToast("Failed to delete customer. Please try again.", "danger");
            },
        });
    });

    // Function to display toast notification
    function showToast(message, type) {
        let toast = $("#deleteToast");
        toast.removeClass("bg-success bg-danger").addClass("bg-" + type);
        toast.find(".toast-body").text(message);
        toast.toast("show");
    }
});

</script>

</body>
</html>
