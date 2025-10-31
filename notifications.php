<?php
session_start();
include 'inc/conn.php';

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

// جلب جميع الإشعارات الخاصة بالمستخدم
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(":user_id", $_SESSION['user']);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// علامة كمقروءة عند فتح الصفحة
$update = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
$update->bindParam(":user_id", $_SESSION['user']);
$update->execute();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الإشعارات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
          
        }
        .notification-card {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            background-color: #fff;
            transition: all 0.3s ease;
        }
        .notification-card.unread {
            border-left: 5px solid #dc3545;
        }
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .notif-title {
            font-weight: bold;
        }
        .notif-date {
            font-size: 0.85rem;
            color: #777;
        }
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
<div class="container mt-5">
    <h3 class="mb-4 text-center">الإشعارات</h3>

    <?php if(count($notifications) == 0): ?>
        <p class="text-center">لا توجد إشعارات جديدة.</p>
    <?php else: ?>
        <?php foreach($notifications as $notif): ?>
            <div class="notification-card <?= $notif['is_read'] == 0 ? 'unread' : '' ?>">
                <div class="notif-title"><?= htmlspecialchars($notif['title']) ?></div>
                <div class="notif-message"><?= htmlspecialchars($notif['message']) ?></div>
                <div class="notif-date"><?= date("Y-m-d H:i", strtotime($notif['created_at'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
