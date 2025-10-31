<?php
session_start();
require('fpdf/fpdf.php');
include 'inc/conn.php';

// Check for selected products
if (empty($_POST['selected_products'])) {
    die("⚠️ No product selected to create the invoice.");
}

// Customer data
$customer_name = $_POST['customer_name'];
$customer_phone = $_POST['customer_phone'];

// Fetch products
$ids = $_POST['selected_products'];
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Setup PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Store logo (put the image path here)
$logoPath = 'img/in-stock.png';
if(file_exists($logoPath)) {
    $pdf->Image($logoPath,10,6,30);
}

// Invoice title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Phone Store Invoice',0,1,'C');
$pdf->Ln(5);

// Customer information
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Customer Name: '.$customer_name,0,1);
$pdf->Cell(0,8,'Phone Number: '.$customer_phone,0,1);
$pdf->Cell(0,8,'Date: '.date('Y-m-d H:i'),0,1);
$pdf->Ln(5);

// Products table
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,200,200); // Header background color
$pdf->Cell(60,10,'Product Name',1,0,'C',true);
$pdf->Cell(30,10,'Quantity',1,0,'C',true);
$pdf->Cell(40,10,'Price (IQD)',1,0,'C',true);
$pdf->Cell(40,10,'Total',1,1,'C',true);

// Table content
$pdf->SetFont('Arial','',12);
$total = 0;
$fill = false; // Shade rows
foreach ($products as $prod) {
    $subtotal = $prod['quantity'] * $prod['price'];
    $total += $subtotal;

    $pdf->SetFillColor(245,245,245); // Alternate background color
    $pdf->Cell(60,10,$prod['name'],1,0,'C',$fill);
    $pdf->Cell(30,10,$prod['quantity'],1,0,'C',$fill);
    $pdf->Cell(40,10,$prod['price'],1,0,'C',$fill);
    $pdf->Cell(40,10,$subtotal,1,1,'C',$fill);

    $fill = !$fill; // Switch color for the next row
}
// تجهيز البيانات لتخزينها
$invoice_details = [];
foreach ($products as $prod) {
    $invoice_details[] = [
        'id' => $prod['id'],
        'name' => $prod['name'],
        'quantity' => $prod['qantity'],
        'price' => $prod['price'],
        'subtotal' => $prod['qantity'] * $prod['price']
    ];
}

// إدخال الفاتورة في قاعدة البيانات
$stmt = $conn->prepare("INSERT INTO invoices (customer_name, customer_phone, total, details) VALUES (:customer_name, :customer_phone, :total, :details)");
$stmt->execute([
    ':customer_name' => $customer_name,
    ':customer_phone' => $customer_phone,
    ':total' => $total,
    ':details' => json_encode($invoice_details, JSON_UNESCAPED_UNICODE)
]);

// Grand Total
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,200,200);
$pdf->Cell(130,10,'Grand Total',1,0,'C',true);
$pdf->Cell(40,10,$total,1,1,'C',true);

// Footer (optional)
$pdf->SetY(-20);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,'Thank you for your business',0,1,'C');

// Save and download the file
$filename = "invoice_".date("Y-m-d_H-i-s").".pdf";
$pdf->Output('D', $filename);
exit;
?>
