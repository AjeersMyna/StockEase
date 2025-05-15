<?php
require_once 'db.php';

class SaleModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerNameById($customerId) {
        if ($customerId) {
            $stmt = $this->conn->prepare("SELECT name FROM customers WHERE id = :id");
            $stmt->bindParam(':id', $customerId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['name'] : '';
        }
        return '';
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $customerId = $_GET['id'];
    $saleModel = new SaleModel($conn);
    $customerName = $saleModel->getCustomerNameById($customerId);
    echo htmlspecialchars($customerName);
} else {
    echo 'Unknown Customer';
}
?>