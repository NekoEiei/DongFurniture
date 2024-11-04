<?php 
    session_start();
    require_once 'config/db.php';
    
    // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
        exit;
    }

    // กำหนดตัวแปร user_login จาก session
    $user_login = $_SESSION['user_login'];

    // อัปเดตสถานะการสั่งทำเมื่อกดปุ่ม อนุมัติ หรือ ปฏิเสธ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $order_id = $_POST['order_id'];
        $new_stage = $_POST['new_stage'];

        $stmt = $conn->prepare("UPDATE order_detail SET order_stage = ? WHERE order_id = ?");
        $stmt->execute([$new_stage, $order_id]);

        $_SESSION['success'] = "อัปเดตสถานะสำเร็จ!";
    }

    // ดึงข้อมูลสถานะการสั่งทำของลูกค้าเฉพาะผู้ใช้ที่ล็อกอินอยู่
    $stmt = $conn->prepare("SELECT order_detail.order_id, order_detail.order_stage, order_detail.price 
    FROM order_detail
    WHERE order_detail.id = :user_login 
    ORDER BY order_detail.id DESC");
    $stmt->execute(['user_login' => $user_login]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/noti.css">
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
                        <a class="nav-link" href="pay.php">จ่ายเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">สถานะการสั่งทำ</a>
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
        <div class="title">
            <h2>สถานะการสั่งทำของคุณ</h2>
        </div>

        <!-- ตรวจสอบว่ามีรายการสั่งทำหรือไม่ -->
        <?php if (count($orders) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>หมายเลขการสั่งทำ</th>
                        <th>สถานะการสั่งทำ</th>
                        <th>ราคา</th>
                        <th>การจัดการการสั่งทำ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['order_stage']; ?></td>
                            <td><?php echo is_numeric($order['price']) ? number_format($order['price'], 2) : 'ยังไม่ได้ประเมิณราคา'; ?> บาท</td>
                            <td>
                                <!-- ฟอร์มสำหรับอนุมัติและปฏิเสธ -->
                                <form action="stage.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <button type="submit" name="new_stage" value="approved" class="btn btn-success btn-sm">อนุมัติ</button>
                                </form>
                                <form action="order_status.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <button type="submit" name="new_stage" value="rejected" class="btn btn-danger btn-sm">ยกเลิก</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">คุณยังไม่มีรายการสั่งทำ</p>
        <?php endif; ?>
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

    <!-- ลิงก์ไปยัง noti -->
    <script src="JS/noti.js"></script>
</body>
</html>