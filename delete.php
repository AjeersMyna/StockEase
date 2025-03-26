<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']); // Ensure it's an integer

        // Check if customer exists before deleting
        $checkStmt = $conn->prepare("SELECT id FROM customers_1 WHERE id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Proceed with deletion
            $stmt = $conn->prepare("DELETE FROM customers_1 WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Customer deleted successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error deleting customer"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Customer not found"]);
        }

        $checkStmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid customer ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
