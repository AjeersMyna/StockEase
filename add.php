<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO real_customers (name, contact_name, phone, email, country, city, state, postal_code, vat, xero_account, invoice_due_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssi", $_POST['name'], $_POST['contact_name'], $_POST['phone'], $_POST['email'], $_POST['country'], $_POST['city'], $_POST['state'], $_POST['postal_code'], $_POST['vat'], $_POST['xero_account'], $_POST['invoice_due_date']);
    
    if ($stmt->execute()) {
        header("Location: customers.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Add New Customer</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact Name</label>
            <input type="text" name="contact_name" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal_code" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">VAT</label>
            <input type="text" name="vat" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Xero Account</label>
            <input type="text" name="xero_account" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Invoice Due Date</label>
            <input type="number" name="invoice_due_date" class="form-control">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Add Customer</button>
        </div>
    </form>
</body>
</html>
