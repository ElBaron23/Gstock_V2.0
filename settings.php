<?php
session_start();
include 'inc/conn.php';

// تأكد أن المستخدم مسجل دخول
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// جلب بيانات المستخدم الحالي
$stmt = $conn->prepare("SELECT * FROM login WHERE id = :id");
$stmt->bindParam(":id", $_SESSION['user']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// تحديث البيانات عند الضغط على زر "حفظ"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['user'];
    $password = $_POST['password'];          // كلمة المرور الجديدة
    $current_password = $_POST['current_password']; // كلمة المرور الحالية للتحقق

    // تحقق من كلمة المرور الحالية
    if ($current_password !== $user['password']) {
        $error = "❌ كلمة المرور الحالية غير صحيحة!";
    } else {
        // إذا كلمة المرور الجديدة غير فاضية، حدّثها
        if (!empty($password)) {
            $update = $conn->prepare("UPDATE login SET name = :name, user = :user, password = :password WHERE id = :id");
            $update->execute([
                ':name' => $name,
                ':user' => $username,
                ':password' => $password,
                ':id' => $_SESSION['user']
            ]);
        } else {
            $update = $conn->prepare("UPDATE login SET name = :name, user = :user WHERE id = :id");
            $update->execute([
                ':name' => $name,
                ':user' => $username,
                ':id' => $_SESSION['user']
            ]);
        }

        $success = "✅ تم تحديث الإعدادات بنجاح";
        // تحديث البيانات بعد الحفظ
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
     <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">

  <title>الإعدادات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">إدارة المخزن</a>
    <div class="d-flex">
        <a class="btn btn-outline-light me-2" href="settings.php">⚙️ الإعدادات</a>
        <a class="btn btn-outline-danger" href="logout.php">تسجيل الخروج</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">إعدادات الحساب</div>
        <div class="card-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">المستخدم (الإيميل)</label>
                    <input type="email" name="user" class="form-control" value="<?= htmlspecialchars($user['user']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-danger">كلمة المرور الحالية (إلزامية للتحديث)</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور الجديدة (اتركها فارغة إذا لا تريد تغييرها)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">💾 حفظ التغييرات</button>
              
            </form>
            <hr>


                
         <form method="POST" action="backup_products.php" class="d-inline">
    <button type="submit" class="btn btn-outline-primary">📦 تحميل نسخة CSV</button>
</form>

<form method="POST" action="backup_products_pdf.php" class="d-inline ms-2">
    <button type="submit" class="btn btn-outline-warning">📄 تحميل نسخة PDF</button>
</form>


        </div>
    </div>
</div>

</body>
</html>
