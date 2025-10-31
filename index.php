<?php
include 'inc/conn.php';
session_start();

$error = '';

if(isset($_POST['login'])){
    $login = $conn->prepare("SELECT * FROM login WHERE user = :user AND password = :password");
    $login->bindParam(":user", $_POST["user"]);
    $login->bindParam(":password", $_POST["password"]);
    $login->execute();

    if($login->rowCount() === 1){
        $user = $login->fetchObject();
        $_SESSION['user'] = $user->id;
        header("Location: home.php");
        exit;
    } else {
        $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gstck</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
</head>
<body>
    
<div class="gcontainer">
    <form method="POST" novalidate>
        <h2 class="text-center mb-4">تسجيل الدخول</h2>

        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="text" class="form-control" placeholder="البريد الإلكتروني" name="user" required>
        </div>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control" placeholder="كلمة المرور" name="password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">تسجيل الدخول</button>
    </form>

    <div class="img">
        <img src="./img/in-stock.png" alt="Gstock" class="img-fluid">
    </div>

    <footer class="text-center mt-3">
        &copy; 2025 جميع الحقوق محفوظة | مطور بواسطة <strong>imad touzouz</strong> | النسخة الحالية v2.0
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
