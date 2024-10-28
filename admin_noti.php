<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // เพิ่มการแจ้งเตือนใหม่
    if (isset($_POST['add_notification'])) {
        $message = $_POST['message'];
        $created_at = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO notifications (message, created_at) VALUES (:message, :created_at)");
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->execute();

        $_SESSION['success'] = 'เพิ่มการแจ้งเตือนสำเร็จ!';
        header('location: admin_manage_notifications.php');
        exit;
    }

    // แก้ไขการแจ้งเตือน
    if (isset($_POST['edit_notification'])) {
        $notification_id = $_POST['notification_id'];
        $message = $_POST['message'];

        $stmt = $conn->prepare("UPDATE notifications SET message = :message WHERE id = :id");
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();

        $_SESSION['success'] = 'แก้ไขการแจ้งเตือนสำเร็จ!';
        header('location: admin_manage_notifications.php');
        exit;
    }

    // ลบการแจ้งเตือน
    if (isset($_POST['delete_notification'])) {
        $notification_id = $_POST['notification_id'];

        $stmt = $conn->prepare("DELETE FROM notifications WHERE id = :id");
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();

        $_SESSION['success'] = 'ลบการแจ้งเตือนสำเร็จ!';
        header('location: admin_manage_notifications.php');
        exit;
    }

    // ดึงข้อมูลการแจ้งเตือนทั้งหมด
    $stmt = $conn->prepare("SELECT * FROM notifications ORDER BY created_at DESC");
    $stmt->execute();
    $notifications = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
                        <a class="nav-link active" aria-current="page" href="admin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_order.php">จัดการการสั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_pay.php">จัดการการจ่ายเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">จัดการการแจ้งเตือน</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php" class="btn btn-outline-danger" style="margin: 20px; font-size: 18px;">Logout</a>
        </div>
    </nav>

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== Admin ====================-->
        <div class="container mt-4">
            <h2>การจัดการการแจ้งเตือน</h2>

            <!-- ฟอร์มเพิ่มการแจ้งเตือน -->
            <form action="" method="post" class="mb-4">
                <div class="mb-3">
                    <label for="message" class="form-label">ข้อความการแจ้งเตือน</label>
                    <input type="text" class="form-control" id="message" name="message" required>
                </div>
                <button type="submit" name="add_notification" class="btn btn-primary">เพิ่มการแจ้งเตือน</button>
            </form>

            <!-- แสดงรายการการแจ้งเตือนทั้งหมด -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ข้อความแจ้งเตือน</th>
                        <th>ส่งมาเมื่อ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $notification) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($notification['message']); ?></td>
                            <td><?php echo htmlspecialchars($notification['created_at']); ?></td>
                            <td>
                                <!-- ปุ่มแก้ไข -->
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                    <input type="text" name="message" value="<?php echo htmlspecialchars($notification['message']); ?>" required>
                                    <button type="submit" name="edit_notification" class="btn btn-warning btn-sm">แก้ไข</button>
                                </form>
                                
                                <!-- ปุ่มลบ -->
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn btn-danger btn-sm" onclick="return confirm('ต้องการลบการแจ้งเตือนนี้?')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- แสดงข้อความสถานะ -->
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success mt-3">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
        </div>
    </main>

    <!-- ลิงก์ไปยัง JS ของ Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzmd57tZ8NfTzPzC2BWe0f9lfpEVl3+BtvhY5lQrH5Lk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>