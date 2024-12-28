<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: admin.php');
    }

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $conn->prepare('SELECT * FROM tbl_order WHERE order_id = :order_id');
        $select_stmt->bindParam(':order_id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        // delete an original record from db
        $delete_stmt = $conn->prepare('DELETE FROM tbl_order WHERE order_id = :order_id');
        $delete_stmt->bindParam(':order_id', $id);
        $delete_stmt->execute();

        header("Location: adminorder.php");
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
    <link rel="stylesheet" href="login.css">
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
                        <a href="">ร้าน ขะ-หนม-ตุ้ย</a>
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
                        <li><a href="">ผู้ดูแลระบบ: <?php echo $row['firstname'] . ' ' . $row['lastname'] ?> </a></li>
                        <a href="admin.php" class="btn btn-primary" id="">ย้อนกลับ</a>
                    </ul>
                </div>
            </div>
        </nav>
        <br>
                <div class="container text-center">
        <h2>รายการจัดส่งสินค้า</h2><br>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td style="width: 100px;">ID: ลูกค้า</td>
                    <td>ชื่อ</td>
                    <td>รายละเอียด</td>
                    <td>ราคา</td>
                    <!-- <td style="width: 100px;"></td> -->
                    <td style="width: 100px;"></td>
                </tr>
            </thead>

            <tbody>
                <?php 
                    $select_stmt = $conn->prepare('SELECT * FROM tbl_order');
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['cake_name']; ?></td>                        
                        <td><?php echo $row['cake_details']; ?></td>
                        <td><?php echo $row['cake_price']; ?></td>                         
                        <!-- <td><a href="addorder.php?update_id=<?php echo $row['id']; ?>" class="btn btn-success">สั่งซื้อ</a></td>-->
                        <!-- <td><a href="?delete_id=<?php echo $row['order_id']; ?>" class="btn btn-danger">ลบ</a></td> -->
                        <td><a href="adminsend.php" class="btn btn-success">ยืนยัน</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
                <!-- <div class="row">
                    <div class="col-sm-1 col-md-3 col-lg-4">
                        <div class="card">
                            <img src="https://down-th.img.susercontent.com/file/088ed198a38552218cfeb86f410f5d0d" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of
                                    the
                                    card's content.</p>
                                <a href="#" class="btn btn-primary">กดสั่งซื้อ</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-3 col-lg-4">
                        <div class="card">
                            <img src="https://i2.wp.com/ligorhomebakery.com/wp-content/uploads/2020/11/RUN_3734-copy.jpg?fit=600%2C600&ssl=1" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of
                                    the
                                    card's content.</p>
                                <a href="#" class="btn btn-primary">กดสั่งซื้อ</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-3 col-lg-4">
                        <div class="card">
                            <img src="https://down-th.img.susercontent.com/file/th-11134207-23020-mvvk3hocjknv4b" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of
                                    the
                                    card's content.</p>
                                <a href="#" class="btn btn-primary">กดสั่งซื้อ</a>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="container">
            <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                <p>© 2024 Company, Inc. All rights reserved.</p>
                <ul class="list-unstyled d-flex">
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24">
                                <use xlink:href="#twitter"></use>
                            </svg></a></li>
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24">
                                <use xlink:href="#instagram"></use>
                            </svg></a></li>
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24">
                                <use xlink:href="#facebook"></use>
                            </svg></a></li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>