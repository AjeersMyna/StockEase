<?php
require_once 'db.php';
require_once 'models/Customer.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Trim and sanitize input
    $name = trim($_POST['name'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $vat = trim($_POST['vat'] ?? '');
    $xero_account = trim($_POST['xero_account'] ?? '');
    $invoice_due_date = trim($_POST['invoice_due_date'] ?? '');

    // Validate required fields
    if (empty($name) || empty($contact_name) || empty($phone) || empty($email) || empty($country)) {
        echo json_encode(["success" => false, "message" => "All required fields (name, contact_name, phone, email, country) must be filled."]);
        exit;
    }

    // Initialize Customer Model
    $customerModel = new Customer($conn);

    // Add Customer
    $result = $customerModel->addCustomer([
        'name' => $name,
        'contact_name' => $contact_name,
        'phone' => $phone,
        'email' => $email,
        'country' => $country,
        'city' => $city,
        'state' => $state,
        'postal_code' => $postal_code,
        'vat' => $vat,
        'xero_account' => $xero_account,
        'invoice_due_date' => $invoice_due_date
    ]);

    echo $result;
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
