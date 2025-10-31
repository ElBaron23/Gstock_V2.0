<?php
session_start();
include 'inc/conn.php';

// جلب المنتجات
$products = $conn->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تجهيز الفاتورة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="mb-4 text-center">🧾 تجهيز الفاتورة</h2>
<form method="POST" action="generate_invoice.php">
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">اسم العميل</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="customer_phone" class="form-control" required>
        </div>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>اختيار</th>
                <th>الاسم</th>
                <th>الكمية</th>
                <th>السعر</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $prod): ?>
            <tr>
                <td><input type="checkbox" name="selected_products[]" value="<?= $prod['id'] ?>"></td>
                <td><?= htmlspecialchars($prod['name']) ?></td>
                <td><?= $prod['quantity'] ?></td>
                <td><?= $prod['price'] ?> د.ع</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        <button type="submit" class="btn btn-success">توليد الفاتورة PDF</button>
    </div>
</form>

</div>

</body>
</html>
