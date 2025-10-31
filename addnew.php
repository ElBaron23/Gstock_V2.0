<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/add.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Tajawal font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body, h2, label, input, button, a {
            font-family: 'Tajawal', Arial, sans-serif !important;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اضافة منتج</title>
</head>
<body dir="rtl">
    <?php
           include 'inc/conn.php';
    session_start();


    $message ="";
    if(isset($_POST["add_product"])){
         $name = trim($_POST['name']);
         $description = trim($_POST['description']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $price_vent = floatval($_POST['price_vent']);

     // التعامل مع رفع الصورة
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetDir = 'img/products/';
        $targetFile = $targetDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $imageName;
        } else {
            $message = "حدث خطأ أثناء رفع الصورة!";
        }
    }

     // إدخال البيانات في قاعدة البيانات
    if ($name && $quantity >= 0 && $price >= 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, quantity, price,price_vent, image) VALUES (:name, :description, :quantity, :price,:price_vent, :image)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":price_vent", $price);
        $stmt->bindParam(":image", $image);
        if ($stmt->execute()) {
            $message = "تمت إضافة المنتج بنجاح!";
             header("location: addnew.php");
        } else {
            $message = "حدث خطأ أثناء حفظ المنتج!";
        }
    } else {
        $message = "الرجاء تعبئة جميع الحقول بشكل صحيح!";
    }

    }

    ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">اضافة منتج</h2>
      <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
     <form method="POST" enctype="multipart/form-data" class="col-md-6 mx-auto">
         <div class="mb-3">
            <label for="name" class="form-label">اسم المنتج</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
         <div class="mb-3">
            <label for="description" class="form-label">وصف المنتج</label>
            <input type="text" name="description" id="description" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">الكمية</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required min="0">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">سعر شراء الوحدة</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" required min="0">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">سعر بيع الوحدة</label>
            <input type="number" name="price_vent" id="price" class="form-control" step="0.01" required min="0">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">صورة المنتج</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" name="add_product" class="btn btn-primary w-100">إضافة المنتج</button>
        <a href="showall.php" class="btn btn-secondary w-100 mt-2">العودة  </a>
    
     </form>

</div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>