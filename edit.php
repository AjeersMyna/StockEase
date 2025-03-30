<?php
require_once 'db.php';
require_once 'models/Customer.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Customer</h2>
        <div id="alert-message" class="alert d-none"></div>

        <form id="editCustomerForm">
            <input type="hidden" name="id" value="<?= $id ?>">
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

    <script>
        $(document).ready(function() {
            $("#editCustomerForm").submit(function(event) {
                event.preventDefault();

                let form = $(this);
                let alertBox = $("#alert-message");

                $.ajax({
                    url: "edit_customer.php",
                    type: "POST",
                    data: form.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $("button[type='submit']").html('<span class="spinner-border spinner-border-sm"></span> Updating...').prop("disabled", true);
                    },
                    success: function(response) {
                        alertBox.removeClass("d-none alert-danger alert-success");

                        if (response.success) {
                            alertBox.addClass("alert-success").text(response.message);

                            // ✅ Redirect after 2 seconds
                            setTimeout(function () {
                                window.location.replace("customers.php");
                            }, 2000);
                        } else {
                            alertBox.addClass("alert-danger").text(response.message);
                        }
                    },
                    error: function() {
                        alertBox.removeClass("d-none alert-success").addClass("alert-danger").text("An error occurred.");
                    },
                    complete: function () {
                        $("button[type='submit']").html("Update").prop("disabled", false);
                    }
                });
            });
        });
    </script>

</body>
</html>
