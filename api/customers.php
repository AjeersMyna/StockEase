<?php
require_once "db.php"; // Ensure this initializes $conn as a PDO instance

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle CORS Preflight Requests
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Check database connection
if (!isset($conn) || !$conn instanceof PDO) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection error."]);
    exit;
}

// Determine request method
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case "GET":
        if (isset($_GET["id"])) {
            getCustomer((int)$_GET["id"]);
        } else {
            getAllCustomers();
        }
        break;

    case "POST":
        addCustomer();
        break;

    case "PUT":
        updateCustomer();
        break;

    case "DELETE":
        if (isset($_GET["id"])) {
            deleteCustomer((int)$_GET["id"]);
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Customer ID is required for deletion."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
        break;
}

// Fetch all customers
function getAllCustomers() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM customers_1");
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $customers]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}

// Fetch a single customer by ID
function getCustomer($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM customers_1 WHERE id = ?");
        $stmt->execute([$id]);
        $customer = $stmt->fetch();

        if ($customer) {
            echo json_encode(["success" => true, "data" => $customer]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Customer not found."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error fetching customer: " . $e->getMessage()]);
    }
}

// Add a new customer
function addCustomer() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data["name"]) || empty($data["email"]) || empty($data["phone"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Name, email, and phone are required."]);
        return;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO customers_1 (name, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([$data["name"], $data["email"], $data["phone"]]);

        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Customer added successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error adding customer: " . $e->getMessage()]);
    }
}

// Update a customer
// Update a customer
function updateCustomer() {
    global $conn;
    $data = $_POST; // Use $_POST instead of JSON for form submission

    if (empty($data["id"]) || empty($data["name"]) || empty($data["email"]) || empty($data["phone"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID, name, email, and phone are required."]);
        return;
    }

    try {
        $query = "UPDATE customers_1 SET name = ?, email = ?, phone = ?, contact_name = ?, country = ?, city = ?, state = ?, postal_code = ?, vat = ?, xero_account = ?, invoice_due_date = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            $data["name"], $data["email"], $data["phone"], 
            $data["contact_name"] ?? null, $data["country"] ?? null, 
            $data["city"] ?? null, $data["state"] ?? null, 
            $data["postal_code"] ?? null, $data["vat"] ?? null, 
            $data["xero_account"] ?? null, $data["invoice_due_date"] ?? null, 
            $data["id"]
        ]);

        // ✅ Redirect to customers.php after successful update
        echo json_encode(["success" => true, "message" => "Customer updated successfully.", "redirect" => "customers.php"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error updating customer: " . $e->getMessage()]);
    }
}


// Delete a customer
function deleteCustomer($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM customers_1 WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(["success" => true, "message" => "Customer deleted successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error deleting customer: " . $e->getMessage()]);
    }
}
?>
