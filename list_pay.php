<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['carpenter_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // ดึงข้อมูลคำสั่งซื้อจากตาราง order_detail
    $stmt = $conn->prepare("SELECT pay_id, id, pay_at, file_path FROM pay ORDER BY pay_at DESC");
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
    <link rel="stylesheet" href="CSS/list.css">
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
                        <a class="nav-link" href="carpenter_stage.php">รายงานความคืบหน้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list_order.php">รายการสั่งทำ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">รายการชำระเงิน</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php" class="btn btn-outline-danger" style="margin: 20px; font-size: 18px;">Logout</a>
        </div>
    </nav>

    <!--==================== MAIN ====================-->
    <main class="main">
        <div class="container mt-5">
            <h2>ข้อมูลการชำระเงินของลูกค้า</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Order Date</th>
                        <th>Download File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo $order['pay_id']; ?></td>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['pay_at']; ?></td>
                            <td>
                                <?php if ($order['file_path']) { ?>
                                    <a href="pay_bill/<?php echo $order['file_path']; ?>" download class="btn btn-primary btn-sm">Download</a>
                                <?php } else { ?>
                                    <span class="text-muted">No file</span>
                                <?php } ?>
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