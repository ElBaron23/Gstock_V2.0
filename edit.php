 <?php
          include 'inc/conn.php';
    session_start();
  
$id = intval($_GET['id']);

// جلب بيانات المنتج
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$product) {
    echo "المنتج غير موجود";
    exit;
}


// عند تعديل المنتج
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $price_vent = $_POST['price_vent'];

    // رفع صورة جديدة إذا تم اختيارها
    $image = $product['image']; // الصورة القديمة
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "img/products/" . $image);
    }

    $update = $conn->prepare("UPDATE products SET name = :name, quantity = :quantity, price = :price, price_vent= :price_vent, image = :image WHERE id = :id");
    $update->execute([
        ':name' => $name,
        ':quantity' => $quantity,
        ':price' => $price,
        ':image' => $image,
        ':id' => $id
        , ':price_vent' => $price_vent
    ]);

    header("Location: showall.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنتج</title>
</head>
<body class="bg-light">
  
<div class="container mt-5">
    <h2 class="mb-4">تعديل المنتج</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">اسم المنتج</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الكمية</label>
            <input type="number" class="form-control" name="quantity" value="<?= $product['quantity'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label"> سعر الشراء</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label"> سعر البيع</label>
            <input type="number" step="0.01" class="form-control" name="price_vent" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الصورة الحالية</label><br>
            <img src="img/products/<?= htmlspecialchars($product['image']) ?>" width="120"><br><br>
            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" name="update" class="btn btn-success">تحديث</button>
        <a href="showall.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>