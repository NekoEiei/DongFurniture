<?php
    session_start();
    require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/png" href="image/newDong logo.png">
</head>
<body>
    <div class="container">
        <a href="">
            <img src="image/newDong logo.png" alt="" width="130px" height="130px" style="border-radius: 15px;">
        </a>
        <h2 class="mt-4">เข้าสู่ระบบ</h2>
        <hr>
        <form action="login_db.php" method="post">
            <?php if(isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label for="E-Mail" class="form-label" style="font-size:20px;">E-Mail</label>
                <input type="email" class="form-control" name="email" aria-describedby="email">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label" style="font-size:20px;">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" name="login" class="btn btn-success" style="width:100px; height:50px; font-size: 20px;">Login</button>
        </form>
        <hr>
        <p>คลิกที่นี้เพื่อ <a href="register.php">Register</a></p>
    </div>
</body>
</html>