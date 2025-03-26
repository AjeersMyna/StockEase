<?php
require_once 'db.php';
require_once 'models/Customer.php';

$customerModel = new Customer($conn);

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$customers = $customerModel->getCustomers($search, $limit, $offset);

echo json_encode(["customers" => $customers]);
?>
