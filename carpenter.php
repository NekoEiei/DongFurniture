<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['carpenter_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // ตรวจสอบการส่งข้อมูลการแจ้งเตือน
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        $message = $_POST['message'];

        if (!empty($user_id) && !empty($message)) {
            // เพิ่มการแจ้งเตือนในฐานข้อมูล
            $stmt = $conn->prepare("INSERT INTO notifications (id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)");
            $stmt->execute([$user_id, $message]);

            $_SESSION['success'] = "ส่งการแจ้งเตือนสำเร็จ!";
        } else {
            $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน!";
        }
    }

    // ดึงรายชื่อผู้ใช้จากฐานข้อมูลเพื่อนำมาเลือกส่งการแจ้งเตือน
    $stmt = $conn->prepare("SELECT id, username FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

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
                        <a class="nav-link active" aria-current="page" href="#">รายงานผล</a>
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
            <h2>ส่งการแจ้งเตือนให้ผู้ใช้</h2>

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
            <form action="carpenter.php" method="POST">
                <div class="mb-3">
                    <label for="user_id" class="form-label">เลือกผู้ใช้</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- เลือกผู้ใช้ --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">ข้อความการแจ้งเตือน</label>
                    <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">ส่งการแจ้งเตือน</button>
            </form>
        </div>
    </main>

    <!-- ลิงก์ไปยัง JS ของ Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzmd57tZ8NfTzPzC2BWe0f9lfpEVl3+BtvhY5lQrH5Lk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>