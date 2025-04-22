<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTodaysSales() {
        $query = "SELECT SUM(total_amount) AS total FROM sales WHERE DATE(sale_date) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getMonthlyRevenue() {
        $query = "SELECT SUM(total_amount) AS total FROM sales WHERE MONTH(sale_date) = MONTH(CURRENT_DATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getLowStockCount() {
        $query = "SELECT COUNT(*) AS count FROM inventory WHERE quantity <= reorder_level";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }

    public function getActiveCustomerCount() {
        $query = "SELECT COUNT(DISTINCT customer_id) AS count FROM sales WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }

    public function getLast6Months() {
        return [
            date('M Y', strtotime('-5 months')),
            date('M Y', strtotime('-4 months')),
            date('M Y', strtotime('-3 months')),
            date('M Y', strtotime('-2 months')),
            date('M Y', strtotime('-1 month')),
            date('M Y')
        ];
    }

    public function getMonthlySalesData() {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $query = "SELECT SUM(total_amount) AS total FROM sales WHERE DATE_FORMAT(sale_date, '%Y-%m') = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$month]);
            $data[] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        }
        return $data;
    }

    public function getInventoryStatusData() {
        $query = "SELECT 
            SUM(CASE WHEN quantity > reorder_level THEN 1 ELSE 0 END) AS in_stock,
            SUM(CASE WHEN quantity > 0 AND quantity <= reorder_level THEN 1 ELSE 0 END) AS low_stock,
            SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) AS out_of_stock
            FROM inventory";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return array_values($stmt->fetch(PDO::FETCH_ASSOC));
    }
}
?>