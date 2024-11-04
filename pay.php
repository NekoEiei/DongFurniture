<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    if (isset($_POST['confirm'])) {
        $id = $_SESSION['user_login'];
        $detail = $_POST['detail'];
        $order_at = date('Y-m-d H:i:s');

        // ตรวจสอบและอัปโหลดไฟล์
        $filename = $_FILES['input-file']['name'];
        $fileTmpName = $_FILES['input-file']['tmp_name'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileExt, $allowed)) {
            // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันการชนกันของชื่อไฟล์
            $newFilename = uniqid() . '.' . $fileExt;
            $uploadPath = 'pay_bill/' . $newFilename;

            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // บันทึกข้อมูลคำสั่งในฐานข้อมูล
                $stmt = $conn->prepare("INSERT INTO pay (id, pay_at, file_path) VALUES (:id, :pay_at, :file_path)");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':pay_at', $pay_at);
                $stmt->bindParam(':file_path', $newFilename);
                $stmt->execute();

                $_SESSION['success'] = 'สั่งทำสำเร็จ!';
                header('location: pay.php');
            } else {
                $_SESSION['error'] = 'อัพโหลดไฟล์ไม่สำเร็จ!';
            }
        } else {
            $_SESSION['error'] = 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG, และ PDF';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/pay.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="image/newDong logo.png">
</head>
<body>
    <!---=========Menu=============-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!---=========Logo=============-->
            <a class="navbar-brand" href="#">
                <img src="image/newDong logo.png" alt="" width="100px" height="100px">
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav" style="font-size: 25px;">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order.php">สั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">จ่ายเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stage.php">สถานะการสั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="noti.php">การแจ้งเตือน</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php" class="btn btn-outline-danger" style="margin: 20px; font-size: 18px;">Logout</a>
        </div>
    </nav>

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== Pay ====================-->
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
        <div class="title">
            <h2>การจ่ายเงิน</h2>
        </div>
        <div class="containerD">
            <div class="payimg">
                <img src="image/qr.jpg" style="height:400px; width:400px; border-radius: 20px; margin-left: 65px;">
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="upload_f" style="margin-left:15px; margin-top:30px;">
                <h2 style="text-align:center; color:#ff4118;">อัพโหลดหลักฐานการชำระเงิน</h2>
                <label for="input-file" id="drop-area">
                    <input type="file" name="input-file" id="input-file" hidden>
                    <div id="img-view">
                        <img src="image/upload.png">
                        <p>อัพโหลดบิลชำระเงินที่นี่</p>
                        <span>ลากเพื่ออัพโหลดไฟล์ของคุณ</span>
                    </div>
                </label>
                <button type="submit" name="confirm" class="btn btn-success" style="width:100px; height:50px; font-size: 20px; margin-top:20px; margin-left:200px;">อัพโหลด</button>
            </form>
        </div>
    </main>

    <!--==================== FOOTER ====================-->
    <footer class="footer">
        <div class="footer__container container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer__info">
                    <h2>DongFurniture</h2>
                    <p>สร้างบ้านในฝันของคุณกับเรา</p>
                </div>
                <div class="col-lg-2 col-md-6 footer__links">
                    <h4>About</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Features</a></li>
                        <li><a href="#">News & Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 footer__links">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">History</a></li>
                        <li><a href="#">Testimonials</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 footer__links">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="#">Call Center</a></li>
                        <li><a href="#">Support Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer__links">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Services</a></li>
                        <li><a href="#">Payments</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer__social">
                <a href="#"><i class="ri-facebook-fill"></i></a>
                <a href="#"><i class="ri-instagram-line"></i></a>
                <a href="#"><i class="ri-twitter-fill"></i></a>
                <a href="#"><i class="ri-youtube-fill"></i></a>
            </div>
            <div class="footer__copyright">
                <p>&copy; Copyright DongFurniture. All rights reserved</p>
            </div>
        </div>
    </footer>

    <!-- ลิงก์ไปยัง JS ของ Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzmd57tZ8NfTzPzC2BWe0f9lfpEVl3+BtvhY5lQrH5Lk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ลิงก์ไปยัง drop -->
    <script src="JS/drop.js"></script>
</body>
</html>