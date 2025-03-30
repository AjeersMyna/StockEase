<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id']) && is_numeric($data['id'])) {
        $id = intval($data['id']);

        try {
            // Check if customer exists before deleting
            $checkStmt = $conn->prepare("SELECT id FROM customers_1 WHERE id = ?");
            $checkStmt->execute([$id]);
            $customerExists = $checkStmt->fetch();

            if ($customerExists) {
                // Delete customer
                $stmt = $conn->prepare("DELETE FROM customers_1 WHERE id = ?");
                if ($stmt->execute([$id])) {
                    echo json_encode(["success" => true, "message" => "Customer deleted successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error deleting customer"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Customer not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid customer ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
