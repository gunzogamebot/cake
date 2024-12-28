<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านเค้กโฮมเมด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="register.css">
    <style>
        body {
          background-color: burlywood; /* กำหนดสีพื้นหลังเป็น #F0F0F0 */
        }
    </style>
</head>

<body>
        <!-- home -->
        <nav>
            <div class="container">
                <div class="nav-con">
                    <div class="logo">
                        <a href="index.html">ร้าน ขะ-หนม-ตุ้ย</a>
                    </div>
                    <ul class="menu">
                        <li><a href="index.html">หน้าแรก</a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        <!-- <a href="login.html" class="sign-btn" id="login">เข้าสู่ระบบ</a> -->
                        <a href="signup.php" class="asign-btn" id="register">สมัครสมาชิก</a>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- home -->

        <main class="form-signin w-100 m-auto">
            <form class="text-center">
                <h1 class="h3 mb-3 fw-normal">เข้าสู่ระบบ</h1>
            </form>
            <form action="signin_db.php" method="post">
            <?php if(isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php 
                        
                            echo $_SESSION['error'];
                            unset($_SESSION['error'])

                        ?>
                    </div>
                <?php } ?>
                <?php if(isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php 
                        
                            echo $_SESSION['success'];
                            unset($_SESSION['success'])

                        ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="email" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" name="email" aria-describedby="email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <button type="submit" name="signin" class="btn btn-primary">เข้าสู่ระบบ</button>
            </form>
            <!-- <a href="order.html" class="sign-btn" id="order">เข้าสู่ระบบ</a> -->
            <!-- <a href="admin.html" class="asign-btn" id="order">เข้าสู่ระบบ Admin</a> -->
        </main>

        <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
            <p>© เบอร์โทร 092-796-5160</p>   
            <ul class="list-unstyled d-flex">
                <li class="ms-3"><p>@ Facebook : Natthaphon</p></a></li>
                <li class="ms-3"><p>@ LINE : Natthaphon</p></a></li>
            </ul>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>