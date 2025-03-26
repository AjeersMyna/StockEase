<?php
require_once 'db.php';
require_once 'models/Customer.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerModel = new Customer($conn);
    
    $id = intval($_POST['id']);
    $data = [
        'name' => $_POST['name'],
        'contact_name' => $_POST['contact_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'country' => $_POST['country'],
        'xero_account' => $_POST['xero_account']
    ];

    if ($customerModel->updateCustomer($id, $data)) {
        echo json_encode(["success" => true, "message" => "Customer updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update customer."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
