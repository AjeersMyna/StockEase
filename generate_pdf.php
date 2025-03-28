<?php
// Include TCPDF library
require_once __DIR__ . '/TCPDF/tcpdf.php';



// Create new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('YourApp');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Customer Report');
$pdf->SetSubject('List of Customers');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a title
$pdf->Cell(0, 10, 'Customer Report', 0, 1, 'C');

// Output PDF to browser
$pdf->Output('customer_report.pdf', 'I');
?>
