<?php
class Sale {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all sales with customer names
    public function getSales($limit = 10, $offset = 0) {
        $query = "SELECT s.*, c.name AS customer_name
                  FROM stockease_customers.sales s
                  LEFT JOIN stockease_customers.customers_1 c ON s.customer_id = c.id
                  ORDER BY s.sale_date DESC
                  LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $offset, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new sale
    public function addSale($data) {
        try {
            $this->conn->beginTransaction();

            // Insert sale
            $query = "INSERT INTO stockease_customers.sales (invoice_number, customer_id, total_amount, sale_date)
                      VALUES (:invoice_no, :customer_id, :total, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':invoice_no' => $data['invoice_number'],
                ':customer_id' => $data['customer_id'],
                ':total' => $data['total_amount']
            ]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Sale Error: " . $e->getMessage());
            return false;
        }
    }

    // Get sale by ID
    public function getSaleById($id) {
        $query = "SELECT s.*, c.name AS customer_name, c.email, c.phone
                  FROM stockease_customers.sales s
                  LEFT JOIN stockease_customers.customers_1 c ON s.customer_id = c.id
                  WHERE s.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>