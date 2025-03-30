<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="text-center mb-4">Add New Customer</h3>
        <div id="alert-message" class="alert d-none"></div> <!-- Success/Error Message -->

        <form id="addCustomerForm" class="row g-3" enctype="multipart/form-data">
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
            <div class="col-md-4">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control">
            </div>
            <div class="col-md-4">
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

            <div class="col-md-12">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">Add Customer</button>
                <a href="customers.php" class="btn btn-secondary w-100 mt-2">Back</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#addCustomerForm").submit(function (event) {
            event.preventDefault(); // Prevent page reload

            let formData = new FormData(this); // Use FormData for file uploads

            $.ajax({
                url: "add_customer.php", // ✅ Correct the filename if needed
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function () {
                    $("button[type='submit']").html('<span class="spinner-border spinner-border-sm"></span> Processing...').prop("disabled", true);
                },
                success: function (response) {
                    let alertBox = $("#alert-message");
                    alertBox.removeClass("d-none alert-danger alert-success");

                    if (response.success) {
                        alertBox.addClass("alert-success").text(response.message);
                        $("#addCustomerForm")[0].reset(); // Clear form fields

                        // ✅ Redirect to customers list after 2 seconds
                        setTimeout(function () {
                            window.location.href = "customers.php";
                        }, 2000);
                    } else {
                        alertBox.addClass("alert-danger").text(response.message);
                    }
                },
                error: function () {
                    $("#alert-message").removeClass("d-none alert-success").addClass("alert-danger").text("An error occurred. Please try again.");
                },
                complete: function () {
                    $("button[type='submit']").html("Add Customer").prop("disabled", false);
                }
            });
        });
    });
</script>

<!-- ✅ Add Bootstrap JavaScript for better UI -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
