<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: user.php');
    }

    if (isset($_REQUEST['update_id'])) {
        try {
            $id = $_REQUEST['update_id'];
            $select_stmt = $conn->prepare('SELECT * FROM tbl_order WHERE order_id = :order_id');
            $select_stmt->bindParam(":order_id", $id);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

    if (isset($_REQUEST['btn_update'])) {
        try {
            $cake_top = $_REQUEST['txt_top'];
            $cake_details = $_REQUEST['txt_details'];
            

            if (!isset($errorMsg)) {
                $update_stmt = $conn->prepare("UPDATE tbl_order SET cake_top = :cake_top_up, cake_details = :cake_details_up WHERE order_id = :order_id");
                $update_stmt->bindParam(':cake_top_up' , $cake_top);
                $update_stmt->bindParam(':cake_details_up', $cake_details);
                $update_stmt->bindParam(':order_id', $order_id);
                if ($update_stmt->execute()) {
                    $updateMsg = "File update successfully...";
                    header("refresh:2;list.php");
                }
            }

        } catch(PDOException $e) {
            $e->getMessage();
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Page</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        body {
          background-color: burlywood; /* กำหนดสีพื้นหลังเป็น #F0F0F0 */
        }
    </style>
</head>
<body>

<div class="background">
    <nav>
        <div class="container">
            <div class="nav-con">
                <div class="logo">
                    <a href="#">ร้านเค้กโฮมเมด</a>
                </div>
                <ul class="menu">
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <!-- <a href="signin.php" class="sign-btn" id="login"></a>
                    <a href="signup.php" class="asign-btn" id="register"></a> -->
                    <?php 
                        
                            if (isset($_SESSION['user_login'])) {
                                $user_id = $_SESSION['user_login'];
                                $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            }
                        
                    ?>
                    <li><a href="#">ID ลูกค้า: <?php echo $row['id']?></a></li>
                    <li><a href="#">USER: <?php echo $row['firstname'] . ' ' . $row['lastname']?></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center"><br>
        <h3>แก้ไขรายการสั่งทำ</h3><br>
        <?php 
            if(isset($errorMsg)) {
        ?>
            <div class="alert alert-danger">
                <strong><?php echo $errorMsg; ?></strong>
            </div>
        <?php } ?>

        <?php 
            if(isset($updateMsg)) {
        ?>
            <div class="alert alert-success">
                <strong><?php echo $updateMsg; ?></strong>
            </div>
        <?php } ?>
        
        <div class="bg">

            <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row">
                    
                    <label for="name" class="col-sm-4 control-label">เลขรายการสั่งซื้อ</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_uid" class="form-control" value="<?php echo $order_id; ?>" readonly>
                    </div>
                    <label for="name" class="col-sm-4 control-label">ขื่อเค้ก</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_name" class="form-control" value="<?php echo $cake_name; ?>" readonly>
                    </div>
                    <label for="name" class="col-sm-4 control-label">รายการท็อปปิ้ง</label>
                    <div class="mb-3 col-sm-5">
                    <?php 
                        $select_stmt = $conn->prepare('SELECT * FROM tbl_product'); 
                        $select_stmt->execute();

                         {
                    ?>
                        <select name="txt_top" class="form-control" required>
                            <option value="">เลือกท็อปปิ้ง</option>
                            <option value="">ไม่เลือกท็อปปิ้ง</option>
                            <?php foreach ($select_stmt as $row) : ?>
                                <option ><?php echo $row['pro_name'] . " / ราคา " . $row['pro_price'] . "บาท" ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php } ?>
                    </div>
                    
                    <label for="name" class="col-sm-4 control-label">ราคา</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_price" class="form-control" value="<?php echo $cake_price; ?>" readonly>
                    </div>
                    <label for="name" class="col-sm-4 control-label">รายละเอียดเพิ่มเติม</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_details" class="form-control" value="<?php echo $cake_details; ?>" >
                    </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" name="btn_update" class="btn btn-success" value="ยืนยัน">
                        <a href="list.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>