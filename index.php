<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index1.css">
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order.php">สั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pay.php">จ่ายเงิน</a>
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
        <!--==================== HOME ====================-->
        <section class="home section" id="home">
            <img src="image/inb.png" alt="home image" class="home__bg" style="width:100%;">
            <div class="home__shadow"></div>
            <div class="title">
                <h2>ห้องต่างๆ</h2>
                <h5>ออกแบบห้องในสไตล์ของคุณเอง</h5>
            </div>
            <div class="home__container container grid">
                <div class="home__cards grid">
                    <article class="home__card">
                        <img src="image/e1.jpg" alt="home image" class="home__card-img">
                        <h3 class="home__card-title">ห้องน้ำ</h3>
                        <div class="home__card-shadow"></div>
                    </article>

                    <article class="home__card">
                        <img src="image/e2.jpg" alt="home image" class="home__card-img">
                        <h3 class="home__card-title">ห้องครัว</h3>
                        <div class="home__card-shadow"></div>
                    </article>

                    <article class="home__card">
                        <img src="image/e3.jpg" alt="home image" class="home__card-img">
                        <h3 class="home__card-title">ห้องนอน</h3>
                        <div class="home__card-shadow"></div>
                    </article>
                </div>
            </div>
        </section>

        <!--==================== other ====================-->
        <section class="explore-travel section">
            <div class="explore-travel__container container grid">
                <div class="explore-travel__data">
                    <h2 class="explore-travel__title">สร้างสรรค์พื้นที่ในฝัน ด้วยเฟอร์นิเจอร์ Built-in สุดพิเศษสำหรับคุณ</h2>
                    <p class="explore-travel__description">
                        ให้เราช่วยออกแบบและสร้างเฟอร์นิเจอร์ในแบบที่คุณต้องการ ปรับเข้ากับทุกพื้นที่ในบ้านหรือสำนักงานของคุณ ด้วยการผลิตคุณภาพสูง ใส่ใจทุกรายละเอียด วัสดุคัดสรรอย่างพิถีพิถัน พร้อมทีมงานผู้เชี่ยวชาญด้านการออกแบบและติดตั้ง
                    </p>
                    <a href="order.php" class="explore-travel__button">สั่งเลยวันนี้ <i class="ri-arrow-right-line"></i></a>
                </div>
                <div class="explore-travel__img">
                    <img src="image/f7.jpg" alt="Travel Image">
                </div>
            </div>
        </section>
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
</body>
</html>