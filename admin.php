<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: admin.php');
    }

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $conn->prepare('SELECT * FROM tbl_cake WHERE cake_id = :cake_id');
        $select_stmt->bindParam(':cake_id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        unlink("image/".$row['image']); // unlin functoin permanently remove your file

        // delete an original record from db
        $delete_stmt = $conn->prepare('DELETE FROM tbl_cake WHERE cake_id = :cake_id');
        $delete_stmt->bindParam(':cake_id', $id);
        $delete_stmt->execute();

        header("Location: admin.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านเค้กโฮมเมด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="order.css">
    <style>
        body {
          background-color: burlywood; /* กำหนดสีพื้นหลังเป็น #F0F0F0 */
        }
    </style>
</head>

<body>
    <div class="background">
        <!-- home -->
        <nav>
            <div class="container">
                <div class="nav-con">
                    <div class="logo">
                        <a href="#">ร้าน ขะ-หนม-ตุ้ย</a>
                    </div>
                    <ul class="menu">
                        <li><a href="toppings.php">รายการท็อปปิ้ง</a></li>
                        <li><a href="adminorder.php">รายการสั่งทำ</a></li>
                        <li><a href="">รายการที่ชำระเงินแล้ว</a></li>
                        <li><a href="adminorderii.php">จัดส่งสินค้า</a></li>
                        <?php 
                        
                            if (isset($_SESSION['admin_login'])) {
                                $user_id = $_SESSION['admin_login'];
                                $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            }
                        
                        ?>
                        <li><a href="">ผู้ดูแลระบบ: <?php echo $row['firstname'] . ' ' . $row['lastname'] ?> </a></li>
                        <a href="logout.php" class="btn btn-primary" id="">ออกจากระบบ</a>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container"><br>
            <h2 class="text-center">รายการสินค้า</h2>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 150px;">รูปภาพ</th>
                        <th>ชื่อสินค้า</th>
                        <th>รายละเอียด</th>
                        <th style="width: 150px;">ราคา(บาท)</th>
                        <td width="100"></td>
                        <td width="100"></td>
                    </tr>
                </thead>
                <div align="right">
                    <a href="addcake.php" class="btn btn-success">เพิ่มรายการ</a>
                </div><br>
                <tbody>
                    <?php 
                        $select_stmt = $conn->prepare('SELECT * FROM tbl_cake'); 
                        $select_stmt->execute();

                        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <td class="text-center"><img src="image/<?php echo $row['image']; ?>" width="100px" height="100px" alt=""></td>
                            <td><?php echo $row['cake_name']; ?></td>
                            <td><?php echo $row['cake_details']; ?></td>
                            <td><?php echo $row['cake_price']; ?></td>                         
                            <td class="text-center"><a href="editcake.php?update_id=<?php echo $row['cake_id']; ?>" class="btn btn-warning">แก้ไข</a></td>                        
                            <td class="text-center"><a href="?delete_id=<?php echo $row['cake_id']; ?>" class="btn btn-danger">ลบ</a></td>                        
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>