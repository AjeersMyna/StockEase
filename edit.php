<?php
require_once 'db.php';
require_once 'models/Customer.php';

// Initialize the customer model
$customerModel = new Customer($conn);

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid customer ID.");
}

$id = intval($_GET['id']);  // Convert to integer for security

// Fetch customer details
$customer = $customerModel->getCustomerById($id);
if (!$customer) {
    die("Customer not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'name' => $_POST['name'],
        'contact_name' => $_POST['contact_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'country' => $_POST['country'],
        'xero_account' => $_POST['xero_account']
    ];

    $updateSuccess = $customerModel->updateCustomer($id, $data);

    if ($updateSuccess) {
        header("Location: customers.php?updated=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error updating customer.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Customer</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Name</label>
                <input type="text" name="contact_name" class="form-control" value="<?= htmlspecialchars($customer['contact_name']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer['country']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Xero Account</label>
                <input type="text" name="xero_account" class="form-control" value="<?= htmlspecialchars($customer['xero_account']) ?>">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="customers.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
