<?php
session_start();
include 'inc/conn.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // جلب اسم الصورة لحذفها من المجلد (إن وجدت)
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prod) {
        if (!empty($prod['image'])) {
            $path = __DIR__ . '/img/products/' . $prod['image'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // حذف السجل من قاعدة البيانات
        $del = $conn->prepare("DELETE FROM products WHERE id = :id");
        $del->bindParam(':id', $id, PDO::PARAM_INT);
        $del->execute();
    }
}

// بعد الحذف ارجع لعرض المنتجات
header("Location: showall.php");
exit;
