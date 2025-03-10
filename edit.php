<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM real_customers WHERE id = $id");
$customer = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact_name = $_POST['contact_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $vat = $_POST['vat'];
    $xero_account = $_POST['xero_account'];
    $invoice_due_date = $_POST['invoice_due_date'];

    $stmt = $conn->prepare("UPDATE real_customers SET name=?, contact_name=?, phone=?, email=?, country=?, city=?, state=?, postal_code=?, vat=?, xero_account=?, invoice_due_date=? WHERE id=?");
    $stmt->bind_param("ssssssssssii", $name, $contact_name, $phone, $email, $country, $city, $state, $postal_code, $vat, $xero_account, $invoice_due_date, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Customer updated successfully!'); window.location.href='customers.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Edit Customer</h2>
    
    <form method="POST" class="row g-3 shadow p-4 bg-white rounded">
        <div class="col-md-6">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" value="<?= $customer['name'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact Name</label>
            <input type="text" name="contact_name" class="form-control" value="<?= $customer['contact_name'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= $customer['phone'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= $customer['email'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= $customer['country'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= $customer['city'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= $customer['state'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal_code" class="form-control" value="<?= $customer['postal_code'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">VAT</label>
            <input type="text" name="vat" class="form-control" value="<?= $customer['vat'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Xero Account</label>
            <input type="text" name="xero_account" class="form-control" value="<?= $customer['xero_account'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Invoice Due Date</label>
            <input type="number" name="invoice_due_date" class="form-control" value="<?= $customer['invoice_due_date'] ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Update Customer</button>
            <a href="customers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

</body>
</html>
