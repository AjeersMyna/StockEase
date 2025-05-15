<?php
require_once 'vendor/autoload.php';
require_once 'db.php';
require_once 'models/Sale.php';
require_once 'models/Customer.php';
require_once 'models/Product.php';

// Check if sale ID is provided
if (!isset($_GET['id'])) {
    die('Sale ID not specified');
}

$saleId = (int)$_GET['id'];
$saleModel = new Sale($conn);
$customerModel = new Customer($conn);
$productModel = new Product($conn);

// Fetch sale data
$sale = $saleModel->getSaleById($saleId);
if (!$sale) {
    die('Sale not found');
}

// Fetch sale items
$stmt = $conn->prepare("
    SELECT p.name, p.sku, si.quantity, si.unit_price
    FROM sale_items si
    JOIN products p ON si.product_id = p.id
    WHERE si.sale_id = ?
");
$stmt->execute([$saleId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('StockEase');
$pdf->SetAuthor('StockEase');
$pdf->SetTitle('Invoice #' . $sale['invoice_number']);
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Logo and Header
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'STOCKEASE', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 6, '123 Business Street', 0, 1, 'L');
$pdf->Cell(0, 6, 'Mumbai, India - 400001', 0, 1, 'L');
$pdf->Ln(10);

// Invoice Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'TAX INVOICE', 0, 1, 'C');
$pdf->Ln(5);

// Invoice Details
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(50, 6, 'Invoice Number:', 0, 0);
$pdf->Cell(0, 6, $sale['invoice_number'], 0, 1);
$pdf->Cell(50, 6, 'Invoice Date:', 0, 0);
$pdf->Cell(0, 6, date('d/m/Y', strtotime($sale['sale_date'])), 0, 1);
$pdf->Ln(10);

// Customer Details
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 6, 'Bill To:', 0, 1);
$pdf->SetFont('helvetica', '', 10);
if ($sale['customer_id']) {
    $pdf->Cell(0, 6, $sale['customer_name'], 0, 1);
    $pdf->Cell(0, 6, 'Phone: ' . $sale['phone'], 0, 1);
    $pdf->Cell(0, 6, 'Email: ' . $sale['email'], 0, 1);
} else {
    $pdf->Cell(0, 6, 'Walk-in Customer', 0, 1);
}
$pdf->Ln(10);

// Items Table
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(90, 7, 'Description', 1, 0);
$pdf->Cell(30, 7, 'Price', 1, 0, 'R');
$pdf->Cell(20, 7, 'Qty', 1, 0, 'R');
$pdf->Cell(30, 7, 'Amount', 1, 1, 'R');

$pdf->SetFont('helvetica', '', 10);
$total = 0;
foreach ($items as $item) {
    $amount = $item['unit_price'] * $item['quantity'];
    $total += $amount;
    
    $pdf->Cell(90, 7, $item['name'] . ' (' . $item['sku'] . ')', 1, 0);
    $pdf->Cell(30, 7, '₹' . number_format($item['unit_price'], 2), 1, 0, 'R');
    $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'R');
    $pdf->Cell(30, 7, '₹' . number_format($amount, 2), 1, 1, 'R');
}

// Total
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(140, 7, 'Total:', 1, 0, 'R');
$pdf->Cell(30, 7, '₹' . number_format($total, 2), 1, 1, 'R');

// Footer
$pdf->Ln(15);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 6, 'Thank you for your business!', 0, 1, 'C');
$pdf->Cell(0, 6, 'Generated on: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

// Output PDF
$pdf->Output('invoice_' . $sale['invoice_number'] . '.pdf', 'I');
?>