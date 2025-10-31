<?php
session_start();
include 'inc/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Include FPDF library
require('fpdf/fpdf.php');

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF file
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Backup - Products List', 0, 1, 'C');
$pdf->Ln(10);

// Table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(80, 10, 'Description', 1);
$pdf->Ln();

// Product data
$pdf->SetFont('Arial', '', 10);
foreach ($products as $prod) {
    $pdf->Cell(10, 10, $prod['id'], 1);
    $pdf->Cell(40, 10, $prod['name'], 1);
    $pdf->Cell(30, 10, $prod['quantity'], 1);
    $pdf->Cell(30, 10, $prod['price'], 1);
    $pdf->Cell(80, 10, mb_strimwidth($prod['description'], 0, 40, "..."), 1);
    $pdf->Ln();
}

// Output the file
$pdf->Output('D', 'products_backup_' . date('Y-m-d_H-i-s') . '.pdf');
exit;
?>
