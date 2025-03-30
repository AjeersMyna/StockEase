<?php
require_once "db.php"; // Ensure the database connection

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$data = $_POST;
$profile_image = null;

// Ensure required fields are present
if (empty($data["name"]) || empty($data["email"]) || empty($data["phone"])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Name, email, and phone are required."]);
    exit;
}

// Handle optional profile image upload
if (!empty($_FILES["profile_picture"]["name"])) {
    $target_dir = "uploads/"; // Ensure this directory exists
    $profile_image = $target_dir . basename($_FILES["profile_picture"]["name"]);

    if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_image)) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error uploading profile picture."]);
        exit;
    }
}

try {
    // Check if profile_image column exists
    $profileColumnExists = columnExists("profile_image");

    if ($profile_image && $profileColumnExists) {
        $query = "INSERT INTO customers_1 (name, email, phone, contact_name, country, city, state, postal_code, vat, xero_account, invoice_due_date, profile_image) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            $data["name"], $data["email"], $data["phone"], 
            $data["contact_name"] ?? null, $data["country"] ?? null, 
            $data["city"] ?? null, $data["state"] ?? null, 
            $data["postal_code"] ?? null, $data["vat"] ?? null, 
            $data["xero_account"] ?? null, $data["invoice_due_date"] ?? null, 
            $profile_image
        ]);
    } else {
        $query = "INSERT INTO customers_1 (name, email, phone, contact_name, country, city, state, postal_code, vat, xero_account, invoice_due_date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            $data["name"], $data["email"], $data["phone"], 
            $data["contact_name"] ?? null, $data["country"] ?? null, 
            $data["city"] ?? null, $data["state"] ?? null, 
            $data["postal_code"] ?? null, $data["vat"] ?? null, 
            $data["xero_account"] ?? null, $data["invoice_due_date"] ?? null
        ]);
    }

    http_response_code(201);
    echo json_encode(["success" => true, "message" => "Customer added successfully."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error adding customer: " . $e->getMessage()]);
}

// Function to check if a column exists
function columnExists($column_name) {
    global $conn;
    $stmt = $conn->prepare("SHOW COLUMNS FROM customers_1 LIKE ?");
    $stmt->execute([$column_name]);
    return $stmt->fetch() ? true : false;
}
?>
