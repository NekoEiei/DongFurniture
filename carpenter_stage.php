<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['carpenter_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // ตรวจสอบการส่งข้อมูลการอัพเดตสถานะการสั่งทำ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $order_id = $_POST['order_id'];
        $order_stage = $_POST['order_stage']; // รับค่าสถานะการสั่งทำจาก input

        if (!empty($order_id) && !empty($order_stage)) {
            // อัปเดตสถานะการสั่งทำในตาราง order_detail
            $stmt = $conn->prepare("UPDATE order_detail SET order_stage = ? WHERE order_id = ?");
            $stmt->execute([$order_stage, $order_id]);

            $_SESSION['success'] = "อัปเดตสถานะการสั่งทำเรียบร้อย!";
        } else {
            $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน!";
        }
    }

    // ดึงข้อมูล order_id และ order_stage ของคำสั่งซื้อทั้งหมดเพื่อนำมาเลือกอัพเดตสถานะ
    $stmt = $conn->prepare("SELECT order_id, order_stage FROM order_detail ORDER BY order_id DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpenter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/admin.css">
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
                        <a class="nav-link active" aria-current="page" href="carpenter.php">ส่งการแจ้งเตือน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carpenter_rate.php">รายงานผลการประเมิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">รายงานความคืบหน้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list_order.php">รายการสั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list_pay.php">รายการชำระเงิน</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php" class="btn btn-outline-danger" style="margin: 20px; font-size: 18px;">Logout</a>
        </div>
    </nav>

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== Carpenter ====================-->
        <div class="containerD">
            <h2>รายงานความคืบหน้า</h2>

            <!-- แสดงข้อความสำเร็จหรือล้มเหลว -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- ฟอร์มส่งการแจ้งเตือน -->
            <form action="carpenter_stage.php" method="POST">
                <div class="mb-3">
                    <label for="order_id" class="form-label">เลือกหมายเลขการสั่งทำ (Order ID)</label>
                    <select name="order_id" id="order_id" class="form-control" required>
                        <option value="">-- เลือกหมายเลขการสั่งทำ --</option>
                        <?php foreach ($orders as $order): ?>
                            <option value="<?php echo $order['order_id']; ?>">
                                Order ID: <?php echo $order['order_id']; ?> (สถานะปัจจุบัน: <?php echo $order['order_stage']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="order_stage" class="form-label">สถานะการสั่งทำใหม่</label>
                    <input type="text" name="order_stage" id="order_stage" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">อัปเดตสถานะ</button>
            </form>
        </div>
    </main>

    <!-- ลิงก์ไปยัง JS ของ Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzmd57tZ8NfTzPzC2BWe0f9lfpEVl3+BtvhY5lQrH5Lk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>