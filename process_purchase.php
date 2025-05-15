<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'db.php';

// Function to return a JSON response
function jsonResponse($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// Check if the request method is POST and the necessary data is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supplier'], $_POST['purchase_date'], $_POST['total_amount'])) {

    $supplier = $_POST['supplier'];
    $purchase_date = $_POST['purchase_date'];
    $total_amount = $_POST['total_amount'];

    $conn->beginTransaction();

    $insert_purchase_query = "INSERT INTO purchases (supplier, purchase_date, total_amount) VALUES (:supplier, :purchase_date, :total_amount)";
    $stmt = $conn->prepare($insert_purchase_query);
    $purchase_result = $stmt->execute([':supplier' => $supplier, ':purchase_date' => $purchase_date, ':total_amount' => $total_amount]);

    if (!$purchase_result) {
        $conn->rollBack();
        jsonResponse(false, "Error inserting purchase: " . implode(" ", $stmt->errorInfo()));
    }

    $conn->commit();
    jsonResponse(true, "Purchase successfully recorded in the purchases table.");

} else {
    jsonResponse(false, "Invalid request or missing data (supplier, purchase_date, or total_amount).");
}

$conn = null;
?>