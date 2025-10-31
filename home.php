<?php
session_start();


include 'inc/conn.php';
if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

// جلب بيانات المستخدم
$stmt = $conn->prepare("SELECT * FROM login WHERE id = :id");
$stmt->bindParam(":id", $_SESSION['user']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// إحصائيات سريعة
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_quantity = $conn->query("SELECT SUM(quantity) FROM products")->fetchColumn();
$total_value = $conn->query("SELECT SUM(quantity * price) FROM products")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
     <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">
    <title>الرئيسية - إدارة المخزن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { transition: 0.3s; cursor: pointer; }
        .card:hover { transform: scale(1.05); }
    </style>
</head>
<body dir="rtl">
<style>
    .navbar .btn {
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.navbar .btn:hover {
    background-color: #495057;
    transform: scale(1.1);
}

.badge {
    font-size: 0.7rem;
}

</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid" style="display: flex; align-items: center; justify-content: space-between;">
    <a class="navbar-brand" href="home.php">إدارة المخزن</a>

    <div class="d-flex align-items-center  gap-2">
<?php
// جلب عدد الإشعارات من قاعدة البيانات حسب المستخدم الحالي
$stmt = $conn->prepare("SELECT COUNT(*) as notif_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
$stmt->bindParam(":user_id", $_SESSION['user']);
$stmt->execute();
$notif_count = $stmt->fetch(PDO::FETCH_ASSOC)['notif_count'];
?>
      <!-- أيقونة الإشعارات -->
<a href="notifications.php" class="btn btn-dark position-relative rounded-circle p-2">
    <i class="fas fa-bell"></i>
    <?php if($notif_count > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $notif_count ?>
    </span>
    <?php endif; ?>
</a>

        <!-- أيقونة الإعدادات -->
        <a href="settings.php" class="btn btn-dark rounded-circle p-2">
            <i class="fas fa-cog"></i>
        </a>

        <!-- أيقونة تسجيل الخروج -->
        <a href="logout.php" class="btn btn-danger rounded-circle p-2">
            <i class="fas fa-sign-out-alt"></i>
        </a>

    </div>
  </div>
</nav>

<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">إدارة المخزن</a>
    <div class="d-flex">
        <a class="btn btn-outline-light ms-2" href="settings.php">⚙️ الإعدادات</a>
        <a class="btn btn-outline-danger" href="logout.php">تسجيل الخروج</a>
    </div>
  </div>
</nav> -->


<div class="container mt-5">
    <h2 class="mb-4 text-center">لوحة التحكم</h2>

    <!-- إحصائيات سريعة -->
    <div class="row text-center mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h4><?= $total_products ?></h4>
                    <p>عدد المنتجات</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h4><?= $total_quantity ?></h4>
                    <p>إجمالي الكمية</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h4><?= number_format($total_value, 2) ?>درهم</h4>
                    <p>القيمة الكلية</p>
                </div>
            </div>
        </div>
    </div>
<hr>
    <!-- Make sure to add this line in the <head> section of your HTML to include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- روابط الأقسام -->
    <div class="row text-center justify-content-center">

        <div class="col-md-6 p-3">
            <div class="d-grid gap-2">
                <a href="addnew.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> اضافة منتج
                </a>
                <a href="showall.php" class="btn btn-secondary">
                    <i class="fas fa-list"></i> عرض المنتجات
                </a>
                <a href="invoice.php" class="btn btn-success">
                    <i class="fas fa-file-invoice"></i> تجهيز الفاتورة
                </a>
                <a href="statistics.php" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> الإحصائيات
                </a>
            </div>
        </div>

       
           
      

      
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
