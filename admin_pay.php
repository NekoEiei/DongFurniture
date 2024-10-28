<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // ตรวจสอบการเข้าสู่ระบบและสิทธิ์การเข้าถึง
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบในฐานะผู้ดูแลระบบ!';
        header('location: login.php');
    }

    // ดึงข้อมูลการจ่ายเงินทั้งหมด
    $stmt = $conn->prepare("SELECT * FROM pay");
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // การลบข้อมูลการจ่ายเงิน
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM pay WHERE pay_id = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "ลบข้อมูลการจ่ายเงินเรียบร้อยแล้ว!";
            header('location: admin_manage_payments.php');
        }
    }

    // การแก้ไขข้อมูลการจ่ายเงิน
    if (isset($_POST['edit'])) {
        $id = $_POST['pay_id'];
        $detail = $_POST['detail'];
        $stmt = $conn->prepare("UPDATE pay SET detail = :detail WHERE pay_id = :id");
        $stmt->bindParam(':detail', $detail);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "แก้ไขข้อมูลการจ่ายเงินเรียบร้อยแล้ว!";
            header('location: admin_manage_payments.php');
        }
    }
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
                        <a class="nav-link" href="#">จัดการการจ่ายเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_noti.php">จัดการการแจ้งเตือน</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php" class="btn btn-outline-danger" style="margin: 20px; font-size: 18px;">Logout</a>
        </div>
    </nav>

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== Admin ====================-->
        <div class="container mt-5">
            <h2>จัดการข้อมูลการจ่ายเงิน</h2>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>

            <!-- ตารางแสดงข้อมูลการจ่ายเงิน -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pay ID</th>
                        <th>User ID</th>
                        <th>Payment Date</th>
                        <th>File Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) { ?>
                        <tr>
                            <td><?php echo $payment['pay_id']; ?></td>
                            <td><?php echo $payment['id']; ?></td>
                            <td><?php echo $payment['pay_at']; ?></td>
                            <td><?php echo $payment['file_path']; ?></td>
                            <td>
                                <a href="?delete=<?php echo $payment['pay_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?');">ลบ</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $payment['pay_id']; ?>">แก้ไข</button>

                                <!-- Modal แก้ไขข้อมูล -->
                                <div class="modal fade" id="editModal<?php echo $payment['pay_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลการจ่ายเงิน</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="pay_id" value="<?php echo $payment['pay_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="detail" class="form-label">รายละเอียดการชำระเงิน</label>
                                                        <textarea name="detail" class="form-control" required><?php echo $payment['detail']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit" name="edit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- ลิงก์ไปยัง JS ของ Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzmd57tZ8NfTzPzC2BWe0f9lfpEVl3+BtvhY5lQrH5Lk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>