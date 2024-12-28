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
            $cake_name = $_REQUEST['txt_name'];
            $cake_details = $_REQUEST['txt_details'];
            $phone = $_REQUEST['txt_phone'];
            $address = $_REQUEST['txt_address'];
            $time = $_REQUEST['txt_time'];
            $cake_price = $_REQUEST['txt_price'];

            $image_file = $_FILES['txt_file']['name'];
            $type = $_FILES['txt_file']['type'];
            $size = $_FILES['txt_file']['size'];
            $temp = $_FILES['txt_file']['tmp_name'];

            $path = "image/" . $image_file; // set upload folder path

            if (empty($id)) {
                $errorMsg = "Please Enter id";
            } else if (empty($cake_name)) {
                $errorMsg = "Please Enter name";
            } else if (empty($cake_details)) {
                $errorMsg = "Please Enter details";
            } else if (empty($phone)) {
                $errorMsg = "Please Enter phone";
            } else if (empty($address)) {
                $errorMsg = "Please Enter address";
            } else if (empty($time)) {
                $errorMsg = "Please Enter time";
            } else if (empty($cake_price)) {
                $errorMsg = "Please Enter price";
            } else if (empty($image_file)) {
                $errorMsg = "please Select Image";
            } else if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                if (!file_exists($path)) { // check file not exist in your upload folder path
                    if ($size < 5000000) { // check file size 5MB
                        move_uploaded_file($temp, 'image/' . $image_file); // move upload file temperory directory to your upload folder
                    } else {
                        $errorMsg = "Your file too large please upload 10MB size"; // error message file size larger than 5mb
                    }
                } else {
                    $errorMsg = "File already exists... Check upload filder"; // error message file not exists your upload folder path
                }
            } else {
                $errorMsg = "Upload JPG, JPEG, PNG & GIF file formate...";
            }

            if (!isset($errorMsg)) {
                $insert_stmt = $conn->prepare('INSERT INTO tbl_...(id, cake_name, cake_details, phone, address, time, cake_price) VALUES(:fid, :fcake_name, :fcake_details, :fphone, :faddress, :ftime, :fcake_price)');
                $insert_stmt->bindParam(':fid', $id);
                $insert_stmt->bindParam(':fcake_name', $cake_name);
                $insert_stmt->bindParam(':fcake_details', $cake_details);
                $insert_stmt->bindParam(':fphone', $phone);
                $insert_stmt->bindParam(':faddress', $address);
                $insert_stmt->bindParam('ftime', $time);
                $insert_stmt->bindParam(':fcake_price', $cake_price);
                $insert_stmt->bindParam(':fimage', $image_file);

                if ($insert_stmt->execute()) {
                    $insertMsg = "File Uploaded successfully...";
                    header('refresh:2;.php');
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
                    <!-- <a href="admin.php" class="btn btn-primary" id="">หน้าหลัก</a> -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
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
        <h4 class="mb-3">บันทึกข้อมูลการจัดส่งสินค้า</h4>
        <form class="needs-validation" novalidate="">
            <div class="row g-3">
            <?php 
                    $select_stmt = $conn->prepare("SELECT * FROM users WHERE id = $user_id");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-sm-6">
                <label for="firstName" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="firstName" placeholder="" value="<?php echo $row['firstname'] ?>" readonly required="">
                <div class="invalid-feedback">
                    Valid first name is required.
                </div>
                </div>

                <div class="col-sm-6">
                <label for="lastName" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="lastName" placeholder="" value="<?php echo $row['lastname'] ?>" readonly required="">
                <div class="invalid-feedback">
                    Valid last name is required.
                </div>
                </div>

                <div class="col-sm-6">
                <label for="email" class="form-label">อีเมล <span class="text-body-secondary"></span></label>
                <input type="email" class="form-control" id="email" placeholder="" value="<?php echo $row['email'] ?>" readonly >
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

                <label for="meeting-time" class="form-label">วัน-เวลา จัดส่งสินค้า :</label>
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
            
            <h4 class="mb-3">ข้อมูลผู้จัดส่งสินค้า</h4>
            <div class="col-sm-6">
                <label for="firstName" class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" id="firstName" placeholder="">
                <div class="invalid-feedback">
                    Valid first name is required.
                </div>
                </div>

                <div class="col-sm-6">
                <label for="" class="form-label">เบอร์โทรติดต่อ <span class="text-body-secondary"></span></label>
                <input type="" class="form-control" id="email" placeholder="" >
                </div>
            <hr class="my-4">
            <button class="w-100 btn btn-primary btn-lg" type="submit">บันทึกข้อมูลจัดส่งสินค้า</button>
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