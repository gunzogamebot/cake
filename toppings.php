<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: admin.php');
    }

    if (isset($_REQUEST['delete_id'])) {
        $pro_id = $_REQUEST['delete_id'];

        $select_stmt = $conn->prepare('SELECT * FROM tbl_product WHERE pro_id = :pro_id');
        $select_stmt->bindParam(':pro_id', $pro_id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        unlink("image/".$row['image']); // unlin functoin permanently remove your file

        // delete an original record from db
        $delete_stmt = $conn->prepare('DELETE FROM tbl_product WHERE pro_id = :pro_id');
        $delete_stmt->bindParam(':pro_id', $pro_id);
        $delete_stmt->execute();

        header("Location: toppings.php");
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
                        <?php 
                        
                            if (isset($_SESSION['admin_login'])) {
                                $user_id = $_SESSION['admin_login'];
                                $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            }
                        
                        ?>
                        <li><a href="">แอดมิน: <?php echo $row['firstname'] . ' ' . $row['lastname'] ?> </a></li>
                        <a href="admin.php" class="btn btn-primary" id="">ย้อนกลับ</a>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container"><br>
            <h2 class="text-center">รายการท็อปปิ้ง</h2>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 150px;">รูปภาพ</th>
                        <th>ชื่อท็อปปิ้ง</th>
                        <th style="width: 250px;">ราคา(บาท)</th>
                        <td width="100"></td>
                        <td width="100"></td>
                    </tr>
                </thead>
                <div align="right">
                    <a href="addproduct.php" class="btn btn-success">เพิ่มรายการ</a>
                </div><br>
                <tbody>
                    <?php 
                        $select_stmt = $conn->prepare('SELECT * FROM tbl_product'); 
                        $select_stmt->execute();

                        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <td class="text-center"><img src="image/<?php echo $row['image']; ?>" width="100px" height="100px" alt=""></td>
                            <td><?php echo $row['pro_name']; ?></td>
                            <td><?php echo $row['pro_price']; ?></td>                         
                            <td class="text-center"><a href="edit.php?update_id=<?php echo $row['pro_id']; ?>" class="btn btn-warning">แก้ไข</a></td>                        
                            <td class="text-center"><a href="?delete_id=<?php echo $row['pro_id']; ?>" class="btn btn-danger">ลบ</a></td>                        
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>