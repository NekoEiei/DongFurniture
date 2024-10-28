<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: login.php');
    }

    // เพิ่มสมาชิกใหม่
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = $_POST['password'];
        $urole = $_POST['urole'];

        if ($password != $_POST['c_password']) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header('location: admin_manage_users.php');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users(username, firstname, lastname, email, address, password, urole) 
                                VALUES(:username, :firstname, :lastname, :email, :address, :password, :urole)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":password", $passwordHash);
        $stmt->bindParam(":urole", $urole);
        $stmt->execute();

        $_SESSION['success'] = 'เพิ่มสมาชิกสำเร็จ!';
        header('location: admin_manage_users.php');
        exit;
    }

    // แก้ไขข้อมูลสมาชิก
    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $urole = $_POST['urole'];

        $stmt = $conn->prepare("UPDATE users SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, address = :address, urole = :urole WHERE id = :id");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":urole", $urole);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ!';
        header('location: admin.php');
        exit;
    }

    // ลบข้อมูลสมาชิก
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        $_SESSION['success'] = 'ลบข้อมูลสำเร็จ!';
        header('location: admin.php');
        exit;
    }

    // ดึงข้อมูลสมาชิกทั้งหมด
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();

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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_order.php">จัดการการสั่งทำ</a>
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
        <div class="container mt-4">
            <h2>การจัดการข้อมูลสมาชิก</h2>

            <!-- ฟอร์มเพิ่มสมาชิก -->
            <form action="" method="post" class="mb-4">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="firstname" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">ที่อยู่</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="c_password" class="form-label">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" id="c_password" name="c_password" required>
                </div>
                <div class="mb-3">
                    <label for="urole" class="form-label">สิทธิ์การใช้งาน</label>
                    <select class="form-control" id="urole" name="urole">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="carpenter">Carpenter</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary">เพิ่มสมาชิก</button>
            </form>

            <!-- แสดงรายการสมาชิกทั้งหมด -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>อีเมล</th>
                        <th>ที่อยู่</th>
                        <th>สิทธิ์การใช้งาน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                            <td><?php echo htmlspecialchars($user['urole']); ?></td>
                            <td>
                                <!-- ปุ่มแก้ไข -->
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                                    <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                                    <select name="urole">
                                        <option value="user" <?php echo $user['urole'] == 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo $user['urole'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="carpenter" <?php echo $user['urole'] == 'carpenter' ? 'selected' : ''; ?>>Carpenter</option>
                                    </select>
                                    <button type="submit" name="edit_user" class="btn btn-warning btn-sm">แก้ไข</button>
                                </form>
                                
                                <!-- ปุ่มลบ -->
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('ต้องการลบสมาชิกนี้?')">ลบ</button>
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