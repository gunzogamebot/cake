<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: user.php');
    }

                        
    if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // เพิ่มข้อมูลลงฐานข้อมูล
    if (isset($_REQUEST['btn_insert'])) {
        try {
            $id = $_REQUEST['txt_uid'];
            $firstname = $_REQUEST['txt_name'];
            $lastname = $_REQUEST['txt_lname'];
            $email = $_REQUEST['txt_email'];
            $phone = $_REQUEST['txt_phone'];
            $address = $_REQUEST['txt_address'];
            $day = $_REQUEST['txt_date'];
            $time = $_REQUEST['txt_time'];

            if (empty($phone)) {
                $errorMsg = "Please Enter phone";
            } else if (empty($address)) {
                $errorMsg = "Please Enter address";
            } else if (empty($day)) {
                $errorMsg = "Please Enter date";
            } else if (empty($time)) {
                $errorMsg = "Please Enter time";
            }

            if (!isset($errorMsg)) {
                $insert_stmt = $conn->prepare('INSERT INTO tbl_dev(id, firstname, lastname, email, phone, address, day, time) VALUES(:fid, :ffirstname, :flastname, :femail, :fphone, :faddress, :fday :ftime)');
                $insert_stmt->bindParam(':fid', $id);
                $insert_stmt->bindParam(':ffirstname', $firstname);
                $insert_stmt->bindParam(':flastname', $lastname);
                $insert_stmt->bindParam(':femail, $email');
                $insert_stmt->bindParam(':fphone', $phone);
                $insert_stmt->bindParam(':faddress', $address);
                $insert_stmt->bindParam('fday', $day);
                $insert_stmt->bindParam('ftime', $time);

                if ($insert_stmt->execute()) {
                    $insertMsg = "File Uploaded successfully...";
                    header('refresh:2;usertest.php');
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
    <title>ร้าน ขะ-หนม-ตุ้ย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index1.css">
    <style>
        body {
          background-color: burlywood; /* กำหนดสีพื้นหลังเป็น #F0F0F0 */
        }
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <div class="nav-con">
                <div class="logo">
                    <a href="#">ร้าน ขะ-หนม-ตุ้ย</a>
                </div>
                <ul class="menu">
                    <!-- <li><a href="#">หน้าแรก</a></li> -->
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <a href="list.php" class="btn btn-primary" id="">ตะกร้าสินค้า</a>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
         <?php 
            if(isset($errorMsg)) {
        ?>
            <div class="alert alert-danger">
                <strong><?php echo $errorMsg; ?></strong>
            </div>
        <?php } ?>

        <?php 
            if(isset($insertMsg)) {
        ?>
            <div class="alert alert-success">
                <strong><?php echo $insertMsg; ?></strong>
        </div>
        <?php } ?>
        <div class="row">
        <div class="row g-2">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text">รายการสั่งทำเค้ก</span>
          <span class="badge bg-primary rounded-pill"></span>
        </h4><br>
        <?php 
                    $select_stmt = $conn->prepare("SELECT * FROM tbl_order WHERE id = $user_id");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                        
                ?>
                
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0"><?php echo $row['cake_name']; ?></h6>
              <small class="text-body-secondary">"<?php echo $row['cake_top']; ?></small>
            </div>
            <span class="text-body-secondary"><?php echo $row['cake_price']; ?></span>
          </li>
          <?php } ?>
          <li class="list-group-item d-flex justify-content-between">
            <?php
                $sql = "SELECT * FROM tbl_order WHERE id = $user_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            
                // สร้างตัวแปรสำหรับเก็บราคารวม
                $total_price = 0;
            
                // วนลูปเพื่อนำข้อมูลสินค้ามาคำนวณราคา
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $price = $row['cake_price'];
                    $total_price += $price;
                ?><?php } ?>
            <span class="text-success"><h5>รวมราคา(บาท)</h5></span>
            <strong class="text-success"><h5><?php echo $total_price; ?></h5></strong>
          </li>
        </ul>
      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">แจ้งที่อยู่จัดส่ง</h4>
        <form class="needs-validation" novalidate="">
            <div class="row g-3">
            <?php 
                    $select_stmt = $conn->prepare("SELECT * FROM users WHERE id = $user_id");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-sm-6">
                <label for="firstName" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="txt_name" placeholder="" value="<?php echo $row['firstname'] ?>" readonly required="">
                <div class="invalid-feedback">
                    Valid first name is required.
                </div>
                </div>

                <div class="col-sm-6">
                <label for="lastName" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="txt_lname" placeholder="" value="<?php echo $row['lastname'] ?>" readonly required="">
                <div class="invalid-feedback">
                    Valid last name is required.
                </div>
                </div>

                <div class="col-sm-6">
                <label for="email" class="form-label">อีเมล <span class="text-body-secondary"></span></label>
                <input type="email" class="form-control" id="txt_email" placeholder="" value="<?php echo $row['email'] ?>" readonly >
                </div>

                <div class="col-sm-6">
                <label for="email" class="form-label">เบอร์โทรศัพท์ <span class="text-body-secondary"></span></label>
                <input type="text" name="txt_phone" class="form-control">
                </div>

                <?php } ?>
                <div class="col-12">
                <label for="address" class="form-label">ที่อยู่จัดส่ง</label>
                <input type="text" name="txt_address" class="form-control">
                </div>

                <label for="meeting-time" class="form-label">วัน-เวลา นัดรับสินค้า :</label>
                    <div class="mb-3 col-sm-5">
                        <input
                        type="date"
                        id=""
                        name="txt_date"
                        value=""
                        min="2024-06-07T00:00"
                        max="2025-06-14T00:00" />
                        <input
                        type="time"
                        id=""
                        name="txt_time"
                        value="" />
                    </div>
            </div>
            
            <hr class="my-4">
            <button class="w-100 btn btn-primary btn-lg" type="submit" name="btn_insert">บันทึกการสั่งทำเค้ก</button>
            </div>
        </form>
      </div>
    </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
        
            <p>
            ช่องทางการติดต่อ :<br>    
            © เบอร์โทร 092-796-5160 <br>
            @ Facebook : Natthaphon niamnuam <br>
            @ LINE : Natthaphon niamnuam</p>   
            <ul class="list-unstyled d-flex">
                <li class="ms-3"><p></p></a></li>
                <li class="ms-3"><p></p></a></li>
            </ul>
        </div>
    </div>
</body>
</html>