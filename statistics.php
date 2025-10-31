<?php
session_start();


include 'inc/conn.php';

// إجمالي عدد المنتجات (كل الأسطر)
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();

// إجمالي الكمية
$total_quantity = $conn->query("SELECT SUM(quantity) FROM products")->fetchColumn();

// القيمة الكلية (الكمية * السعر)
$total_value = $conn->query("SELECT SUM(quantity * price) FROM products")->fetchColumn();

// المنتج الأكثر كمية
$top_product = $conn->query("SELECT name, quantity FROM products ORDER BY quantity DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// بيانات للرسم البياني
$chartData = $conn->query("SELECT name, quantity FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إحصائيات المخزن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light" dir="rtl">
    <a href="home.php" class="btn btn-secondary mb-4 mt-4 me-2">العودة إلى الصفحة الرئيسية</a>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📊 إحصائيات المخزن</h2>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h4><?= $total_products ?></h4>
                    <p>إجمالي المنتجات</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h4><?= $total_quantity ?></h4>
                    <p>إجمالي الكمية</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h4><?= number_format($total_value, 2) ?> درهم</h4>
                    <p>القيمة الكلية</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white mb-3">
                <div class="card-body">
                    <h4><?= htmlspecialchars($top_product['name']) ?></h4>
                    <p>أكثر منتج كمية (<?= $top_product['quantity'] ?>)</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5">📈 الكميات لكل منتج</h4>
    <canvas id="productChart" height="120"></canvas>
</div>

<script>
    const ctx = document.getElementById('productChart').getContext('2d');
    const productChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($chartData, 'name')) ?>,
            datasets: [{
                label: 'الكمية',
                data: <?= json_encode(array_column($chartData, 'quantity')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>
