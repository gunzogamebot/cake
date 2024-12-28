<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: admin.php');
    }

    if (isset($_REQUEST['btn_insert'])) {
        try {
            $cake_name = $_REQUEST['txt_name'];
            $cake_details = $_REQUEST['txt_details'];
            $cake_price = $_REQUEST['txt_price'];

            $image_file = $_FILES['txt_file']['name'];
            $type = $_FILES['txt_file']['type'];
            $size = $_FILES['txt_file']['size'];
            $temp = $_FILES['txt_file']['tmp_name'];

            $path = "image/" . $image_file; // set upload folder path

            if (empty($cake_name)) {
                $errorMsg = "Please Enter name";
            } else if (empty($cake_details)) {
                $errorMsg = "Please Enter details";
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
                $insert_stmt = $conn->prepare('INSERT INTO tbl_cake(cake_name, cake_details, cake_price, image) VALUES(:fcake_name, :fcake_details, :fcake_price, :fimage)');
                $insert_stmt->bindParam(':fcake_name', $cake_name);
                $insert_stmt->bindParam(':fcake_details', $cake_details);
                $insert_stmt->bindParam(':fcake_price', $cake_price);
                $insert_stmt->bindParam(':fimage', $image_file);

                if ($insert_stmt->execute()) {
                    $insertMsg = "File Uploaded successfully...";
                    header('refresh:2;admin.php');
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
                    <a href="#">ร้าน ขะ-หนม-ตุ้ย</a>
                </div>
                <ul class="menu">
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <li><a href="#"></a></li>
                    <!-- <a href="signin.php" class="sign-btn" id="login"></a>
                    <a href="signup.php" class="asign-btn" id="register"></a> -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center"><br>
        <h3>เพิ่มรายการสินค้า</h3><br>
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
        
        <div class="bg">
            <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row">

                    <label for="name" class="col-sm-4 control-label">ขื่อ</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_name" class="form-control" placeholder="">
                    </div>
                    <label for="name" class="col-sm-4 control-label">รายละเอียด</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_details" class="form-control" placeholder="">
                    </div>
                    <label for="name" class="col-sm-4 control-label">ราคา</label>
                    <div class="mb-3 col-sm-5">
                        <input type="text" name="txt_price" class="form-control" placeholder="">
                    </div>
                    
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <label for="name" class="col-sm-4 control-label">File</label>
                    <div class="mb-3 col-sm-5">
                        <input type="file" name="txt_file" class="form-control">
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
                        <a href="admin.php" class="btn btn-danger">Cancel</a>
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