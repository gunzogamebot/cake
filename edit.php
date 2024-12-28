<?php 

    require_once('config/db.php');

    if (isset($_REQUEST['update_id'])) {
        try {
            $pro_id = $_REQUEST['update_id'];
            $select_stmt = $conn->prepare('SELECT * FROM tbl_product WHERE pro_id = :pro_id');
            $select_stmt->bindParam(":pro_id", $pro_id);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

    if (isset($_REQUEST['btn_update'])) {
        try {

            $pro_name = $_REQUEST['txt_name'];
            $pro_price = $_REQUEST['txt_price'];

            $image_file = $_FILES['txt_file']['name'];
            $type = $_FILES['txt_file']['type'];
            $size = $_FILES['txt_file']['size'];
            $temp = $_FILES['txt_file']['tmp_name'];

            $path = "image/".$image_file;
            $directory = "image/"; // set uplaod folder path for upadte time previos file remove and new file upload for next use

            if ($image_file) {
                if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                    if (!file_exists($path)) { // check file not exist in your upload folder path
                        if ($size < 5000000) { // check file size 5MB
                            unlink($directory.$row['image']); // unlink functoin remove previos file
                            move_uploaded_file($temp, 'image/'.$image_file); // move upload file temperory directory to your upload folder
                        } else {
                            $errorMsg = "Your file to large please upload 5MB size";
                        }
                    } else {
                        $errorMsg = "File already exists... Check upload folder";
                    }
                } else {
                    $errorMsg = "Upload JPG, JPEG, PNG & GIF formats...";
                }
            } else {
                $image_file = $row['image']; // if you not select new image than previos image same it is it.
            }

            if (!isset($errorMsg)) {
                $update_stmt = $conn->prepare("UPDATE tbl_product SET pro_name = :pro_name_up, pro_price = :pro_price_up, image = :file_up WHERE pro_id = :pro_id");
                $update_stmt->bindParam(':pro_name_up', $pro_name);
                $update_stmt->bindParam(':pro_price_up', $pro_price);
                $update_stmt->bindParam(':file_up', $image_file);
                $update_stmt->bindParam(':pro_id', $pro_id);

                if ($update_stmt->execute()) {
                    $updateMsg = "File update successfully...";
                    header("refresh:2;toppings.php");
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
    <br>
    <div class="container text-center">
        <h3>แก้ไขท็อปปิ้ง</h3><br>
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

        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <div class="row">

            <label for="name" class="col-sm-4 control-label">ขื่อ</label>
            <div class="mb-3 col-sm-5">
                <input type="text" name="txt_name" class="form-control" value="<?php echo $pro_name; ?>">
            </div>
            <label for="name" class="col-sm-4 control-label">ราคา</label>
            <div class="mb-3 col-sm-5">
                <input type="text" name="txt_price" class="form-control" value="<?php echo $pro_price; ?>">
            </div>

            </div>
        </div>
        <div class="form-group">
            <div class="row">
            <label for="name" class="col-sm-4 control-label">File</label>
            <div class="mb-3 col-sm-5">
                <input type="file" name="txt_file" class="form-control" value="<?php echo $image ?>"><br>
                <p>
                    <img src="image/<?php echo $image; ?>" height="100" width="100" alt="">
                </p>
            </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <input type="submit" name="btn_update" class="btn btn-success" value="อัพเดท">
                <a href="toppings.php" class="btn btn-danger">ยกเลิก</a>
            </div>
        </div>
    </form>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>