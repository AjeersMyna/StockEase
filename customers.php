<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #F5F5DC; /* Seashell */
        }
        h2 {
            color: #2c3e50; /* Darker, more professional heading color */
            font-weight: 700;
        }
        .btn-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
        }
        .btn-primary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            border-color: #90CAF9 !important;
        }
        .btn-warning {
            background-color: #FFFACD !important; /* Light Yellowish */
            color: #2c3e50 !important;
            border-color: #FFFACD !important;
        }
        .btn-warning:hover {
            background-color: #FFF8E1 !important;
            border-color: #FFF8E1 !important;
        }
        .btn-danger {
            background-color: #F08080 !important; /* Light Coral */
            color: #fff !important;
            border-color: #F08080 !important;
        }
        .btn-danger:hover {
            background-color: #FA8072 !important; /* Lighter shade on hover */
            border-color: #FA8072 !important;
        }
        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #F8F8FF; /* Very light gray for stripes */
        }
        .table-bordered {
            border-color: #E0EEE0; /* Light Honeydew */
        }
        .table-bordered thead th {
            background-color: #E0EEE0; /* Honeydew */
            color: #2c3e50;
            border-bottom-width: 2px;
            border-bottom-color: #ADD8E6;
        }
        .table-bordered th,
        .table-bordered td {
            border-color: #E0EEE0;
        }
        .form-control {
            border-color: #ADD8E6;
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #87CEFA; /* Light Powder Blue */
            box-shadow: 0 0 0 0.2rem rgba(135, 206, 250, 0.25);
        }
        .form-select {
            border-color: #ADD8E6;
            border-radius: 0.5rem;
        }
        .form-select:focus {
            border-color: #87CEFA; /* Light Powder Blue */
            box-shadow: 0 0 0 0.2rem rgba(135, 206, 250, 0.25);
        }
        .pagination .page-link {
            color: #2c3e50;
            background-color: #fff;
            border-color: #E0EEE0;
            border-radius: 0.5rem;
            margin: 0 0.125rem;
        }
        .pagination .page-link:hover {
            color: #2c3e50;
            background-color: #F0F8FF;
            border-color: #E0EEE0;
        }
        .pagination .active .page-link {
            color: #fff;
            background-color: #ADD8E6;
            border-color: #ADD8E6;
        }
        .pagination .disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #E0EEE0;
            cursor: not-allowed;
        }
        .toast {
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        }
        .toast-header {
            background-color: #E0EEE0;
            color: #2c3e50;
            border-bottom-color: #ADD8E6;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .toast-body {
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

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

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="deleteToast" class="toast bg-success text-white" role="alert">
        <div class="toast-body"></div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#entriesPerPage').on('change', function () {
        const limit = $(this).val();
        const search = $('#search').val();
        window.location.href = `?search=${encodeURIComponent(search)}&limit=${limit}&page=1`;
    });

    $('#search').on('keypress', function (e) {
        if (e.which === 13) {
            const limit = $('#entriesPerPage').val();
            const search = $(this).val();
            window.location.href = `?search=${encodeURIComponent(search)}&limit=${limit}&page=1`;
        }
    });

    $(document).on("click", ".delete-btn", function () {
        let customerId = $(this).data("id");
        if (!confirm("Are you sure you want to delete this customer?")) return;

        $.ajax({
            url: "delete.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ id: customerId }),
            dataType: "json",
            success: function (response) {
                if (response.success) {
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
