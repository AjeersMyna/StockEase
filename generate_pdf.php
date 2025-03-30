<?php
// Include TCPDF library
require_once __DIR__ . '/TCPDF/tcpdf.php';

// Create new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('StockEase');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Customer Report');
$pdf->SetSubject('List of Customers');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a title
$pdf->Cell(0, 10, 'Customer Report', 0, 1, 'C');
$pdf->Ln(5); // Line break

// Fetch Data from Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "stockease_customers";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, phone FROM customers_1";
$result = $conn->query($sql);

// Set table width
$tableWidth = 150; // Total width of the table
$pageWidth = $pdf->GetPageWidth(); // Get page width
$startX = ($pageWidth - $tableWidth) / 2; // Center position

$pdf->SetX($startX); // Move to center

// Table Headers
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(50, 10, 'Customer Name', 1, 0, 'C');
$pdf->Cell(50, 10, 'Email', 1, 0, 'C');
$pdf->Cell(50, 10, 'Phone', 1, 1, 'C');

$pdf->SetFont('helvetica', '', 10);

// Display Data in Table
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->SetX($startX); // Align table in center
        $pdf->Cell(50, 10, $row['name'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['email'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['phone'], 1, 1, 'C');
    }
} else {
    $pdf->SetX($startX);
    $pdf->Cell(150, 10, 'No customer data found.', 1, 1, 'C');
}

// Close database connection
$conn->close();

// Output PDF to browser
$pdf->Output('customer_report.pdf', 'I');
?>
