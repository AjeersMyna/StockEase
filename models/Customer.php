<?php
include "db.php"; // Database connection

class Customer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customers_1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCustomer($id, $data) {
        $sql = "UPDATE customers_1 SET 
                name = ?, 
                contact_name = ?, 
                phone = ?, 
                email = ?, 
                country = ?, 
                xero_account = ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return json_encode(["success" => false, "message" => "SQL Error: " . $this->conn->error]);
        }
    
        $stmt->bind_param(
            "ssssssi",
            $data["name"],
            $data["contact_name"],
            $data["phone"],
            $data["email"],
            $data["country"],
            $data["xero_account"],
            $id
        );
    
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }

    public function getCustomers($search = "", $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM customers_1 WHERE 1";
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
        }
        $sql .= " LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        if (!empty($search)) {
            $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addCustomer($data) {
        // Handle missing optional fields
        $city = $data['city'] ?? null;
        $state = $data['state'] ?? null;
        $postal_code = $data['postal_code'] ?? null;
        $vat = $data['vat'] ?? null;
        $xero_account = $data['xero_account'] ?? null;
        $invoice_due_date = $data['invoice_due_date'] ?? null;

        $stmt = $this->conn->prepare("INSERT INTO customers_1 (name, contact_name, phone, email, country, city, state, postal_code, vat, xero_account, invoice_due_date) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            return json_encode(["success" => false, "message" => "SQL Error: " . $this->conn->error]);
        }

        $stmt->bind_param(
            "ssssssssssi",
            $data['name'],
            $data['contact_name'],
            $data['phone'],
            $data['email'],
            $data['country'],
            $city,
            $state,
            $postal_code,
            $vat,
            $xero_account,
            $invoice_due_date
        );

        $result = $stmt->execute();
        if (!$result) {
            return json_encode(["success" => false, "message" => "Error adding customer: " . $stmt->error]);
        }

        return json_encode(["success" => true, "message" => "Customer added successfully!"]);
    }

    public function getTotalCustomers($search = "") {
        if (!empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM customers_1 WHERE name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        } else {
            $sql = "SELECT COUNT(*) as total FROM customers_1";
            $stmt = $this->conn->prepare($sql);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}
?>
