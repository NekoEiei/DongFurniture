<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // ฟังก์ชันการลบข้อมูลการสั่งทำ
    if (isset($_GET['delete'])) {
        $order_id = $_GET['delete'];

        // ลบไฟล์แนบ
        $stmt = $conn->prepare("SELECT file_path FROM order_detail WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order && $order['file_path'] && file_exists('order_bp/' . $order['file_path'])) {
            unlink('order_bp/' . $order['file_path']);
        }

        // ลบข้อมูลคำสั่งทำในฐานข้อมูล
        $stmt = $conn->prepare("DELETE FROM order_detail WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $_SESSION['success'] = 'ลบข้อมูลคำสั่งทำเรียบร้อยแล้ว!';
        header('location: admin_order.php');
    }

    // ฟังก์ชันการเพิ่มและแก้ไขข้อมูลคำสั่งทำ
    if (isset($_POST['save'])) {
        $order_id = $_POST['order_id'];
        $detail = $_POST['detail'];
        $order_at = date('Y-m-d H:i:s');
        $newFilename = null;

        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            $filename = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'pdf');

            if (in_array($fileExt, $allowed)) {
                $newFilename = uniqid() . '.' . $fileExt;
                $uploadPath = 'order_bp/' . $newFilename;

                if (!move_uploaded_file($fileTmpName, $uploadPath)) {
                    $_SESSION['error'] = 'อัพโหลดไฟล์ไม่สำเร็จ!';
                }
            } else {
                $_SESSION['error'] = 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG, และ PDF';
            }
        }

        if ($order_id) {
            // อัปเดตข้อมูลคำสั่งทำ
            $sql = "UPDATE order_detail SET detail = :detail, order_at = :order_at";
            if ($newFilename) {
                $sql .= ", file_path = :file_path";
            }
            $sql .= " WHERE order_id = :order_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':detail', $detail);
            $stmt->bindParam(':order_at', $order_at);
            if ($newFilename) {
                $stmt->bindParam(':file_path', $newFilename);
            }
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            $_SESSION['success'] = 'อัปเดตข้อมูลคำสั่งทำเรียบร้อยแล้ว!';
        } else {
            // เพิ่มข้อมูลคำสั่งทำใหม่
            $stmt = $conn->prepare("INSERT INTO order_detail (id, detail, price, period, room, order_at, file_path) VALUES (:id, :detail, :price, :period, :room, :order_at, :file_path)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':detail', $detail);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':period', $period);
            $stmt->bindParam(':room', $room);
            $stmt->bindParam(':order_at', $order_at);
            $stmt->bindParam(':file_path', $newFilename);
            $stmt->execute();

            $_SESSION['success'] = 'เพิ่มข้อมูลคำสั่งทำเรียบร้อยแล้ว!';
        }

        header('location: admin_order.php');
    }

    // ดึงข้อมูลการสั่งทำทั้งหมด
    $stmt = $conn->prepare("SELECT * FROM order_detail ORDER BY order_at DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
                        <a class="nav-link" href="#">จัดการการสั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_pay.php">จัดการการจ่ายเงิน</a>
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
            <h2>จัดการข้อมูลการสั่งทำ</h2>

            <?php if(isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php } ?>

            <form action="" method="post" enctype="multipart/form-data" class="mb-3">
                <input type="hidden" name="order_id" id="order_id">
                <div class="mb-3">
                    <label for="detail" class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="detail" id="detail" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">แนบไฟล์ (เลือกไฟล์ใหม่เพื่ออัปเดต)</label>
                    <input type="file" class="form-control" name="file" id="file" accept=".jpg, .jpeg, .png, .pdf">
                </div>
                <button type="submit" name="save" class="btn btn-primary">บันทึก</button>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>หมายเลขคำสั่งซื้อ</th>
                        <th>รหัสลูกค้า</th>
                        <th>ประเภทห้อง</th>
                        <th>รายละเอียด</th>
                        <th>รอบการแบ่งจ่าย</th>
                        <th>วันที่สั่งทำ</th>
                        <th>ไฟล์แบบแปลน</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['room']; ?></td>
                            <td><?php echo $order['detail']; ?></td>
                            <td><?php echo $order['period']; ?></td>
                            <td><?php echo $order['order_at']; ?></td>
                            <td>
                                <?php if ($order['file_path']) { ?>
                                    <a href="order_bp/<?php echo $order['file_path']; ?>" download>Download</a>
                                <?php } else { ?>
                                    <span class="text-muted">No file</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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