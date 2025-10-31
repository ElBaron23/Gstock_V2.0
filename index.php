<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gstck</title>
    <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <?php
    include 'inc/conn.php';
    session_start();
    if(isset($_POST['login'])){
        // Replace 'email' and 'password' below with the actual column names in your 'login' table if they are different
        $login = $conn->prepare("SELECT * FROM login WHERE user = :user AND password = :password");
        $login->bindParam(":user", $_POST["user"]);
        $login->bindParam(":password", $_POST["password"]);
        $login->execute();
       
    if($login->rowCount() === 1){
        $user = $login->fetchObject();
        $_SESSION['user'] = $user->id;
        header("Location: home.php");
        header("location:home.php",true);
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
    }


?>
<div class="gcontainer">
    <form method="POST">
     <h1>Login</h1>

     <div class="col-md-6" style="padding: 8px; width: 90%;">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="text" class="form-control" id="inputEmail4" name="user">
  </div>

<div class="col-md-6" style="padding: 8px; width: 90%;">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" class="form-control"  name="password" id="inputPassword4">
  </div>

  <button class="btn btn-primary  mt-3" name="login" type="submit" style="width: 90%;">login</button>
    </form>


    <div class="img">
        <img src="./img/in-stock.png" alt="Gstock">
    </div>

</div>
<footer class="text-center mt-4 mb-2 text-muted small">
    &copy; 2025 جميع الحقوق محفوظة | مطور بواسطة <strong>imad touzouz</strong> | النسخة الحالية v2.0
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>