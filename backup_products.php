<?php
session_start();
include 'inc/conn.php';

// Make sure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// File name
$filename = "products_backup_" . date('Y-m-d_H-i-s') . ".csv";

// Download headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Open file in memory
$output = fopen('php://output', 'w');

// Write column headers
fputcsv($output, ['ID', 'Name', 'Description', 'Quantity', 'Price', 'Image']);

// Fetch data from the table
$stmt = $conn->query("SELECT * FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

// Close the file
fclose($output);
exit;
?>
