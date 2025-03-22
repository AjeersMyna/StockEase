<?php
include "db.php"; // Database connection

class Customer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Update customer details
public function updateCustomer($id, $data) {
    $sql = "UPDATE customers SET 
            name = ?, 
            contact_name = ?, 
            phone = ?, 
            email = ?, 
            country = ?, 
            city = ?, 
            state = ?, 
            postal_code = ?, 
            vat = ?, 
            xero_account = ?, 
            invoice_due_date = ? 
            WHERE id = ?";
    
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        die("Error in SQL query: " . $this->conn->error);
    }

    $stmt->bind_param(
        "sssssssssssi",
        $data["name"],
        $data["contact_name"],
        $data["phone"],
        $data["email"],
        $data["country"],
        $data["city"],
        $data["state"],
        $data["postal_code"],
        $data["vat"],
        $data["xero_account"],
        $data["invoice_due_date"],
        $id
    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


    public function getCustomers($search = "", $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM customers WHERE 1";
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

    public function getTotalCustomers($search = "") {
        if (!empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM customers WHERE name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        } else {
            $sql = "SELECT COUNT(*) as total FROM customers";
            $stmt = $this->conn->prepare($sql);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
    
}
?>
