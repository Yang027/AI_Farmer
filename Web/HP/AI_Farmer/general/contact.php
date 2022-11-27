<?php
require_once '../config.php';
require_once "../control.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <title>協助</title>

    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">


</head>

<body>
    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    if (isset($_POST["con"])) {

        include dirname(__FILE__) . '/PHPMailer/src/Exception.php';
        include dirname(__FILE__) . '/PHPMailer/src/PHPMailer.php';
        include dirname(__FILE__) . '/PHPMailer/src/SMTP.php';
        $mail = new PHPMailer();
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = "big5"; //設定郵件編碼 
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->CharSet = "utf-8";    //信件編碼
        $mail->Username = "aifarmer2022@gmail.com";        //帳號，例:example@gmail.com
        $mail->Password = "mbqnyrjkygdkzdzy";        //密碼
        $mail->IsSMTP();
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        $mail->Encoding = "base64";

        $mail->IsHTML(true);     //內容HTML格式
        $mail->From = $_POST["mail"]; //寄件者
        $mail->FromName = $_POST["mail"]; //寄件者
        $mail->Subject = "尋求幫助";     //信件主旨
        $mail->Body =  $_POST["suggest"]; //信件內容
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->AddAddress("aifarmer2022@gmail.com");   //收件者信箱
        if ($mail->Send()) {
            // echo "寄信成功";
        } else {
            echo "寄信失敗";
            //echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
    ?>
    <form action="contact.php" method="post">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
            <div class="container-fluid">
                <a class="navbar-brand" href="../homepage_logout.php" style="color: #763c16;">
                    <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                    AI Farmer
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../homepage_logout.php" style="color: #1D3124;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="all.php">知識庫</a>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                知識庫
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="fruit.php">水果</a></li>
                                <li><a class="dropdown-item" href="veg.php">蔬菜</a></li>
                                <li><a class="dropdown-item" href="flower.php">花卉</a></li>
                                <li><a class="dropdown-item" href="other.php">其他</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                病蟲害知識庫
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="pest_search.php">蟲害查詢</a></li>
                                <li><a class="dropdown-item" href="disease_search.php">病害查詢</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                聯絡我們
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="contact.php">尋求協助</a></li>
                                <li><a class="dropdown-item" href="suggest.php">給予建議</a></li>
                            </ul>
                        </li>
                        <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
                    </ul>
                    <!-- <a class="nav-link" href="manage.html" style="color: #1D3124;">管理</a> -->

                    <!-- <button type="button" class="btn btn-outline-secondary" location.href="login.html">登入</button> -->
                    <input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = '<?php echo $login_url; ?>'">
                </div>
            </div>
        </nav>


        </br>
        <div style="border:2px #ccc solid;border-radius:10px;background-color:#B5CAA0">
            </br>
            <div class="row mb-3 justify-content-md-center">

                <label for="exampleFormControlInput1" class="form-label col-sm-1 col-form-label text-center">電子信箱</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="請填入您的電子信箱 name@example.com" name="mail">
                </div>
            </div>
            <div class="row mb-3 justify-content-md-center">
                <label for="exampleFormControlTextarea1" class="form-label col-sm-1 col-form-label text-center">建議</label>
                <div class="col-sm-6">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="請寫下您的疑問，我們會盡快回覆您" name="suggest"></textarea>
                </div>
            </div>
            <div class="d-md-flex justify-content-md-center">
                <button type="submit" class="btn" name="con" style="background-color:#F6F4E8;">送出</button>
            </div>
        </div>





        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="../js/bootstrap.bundle.min.js"></script>
        <script src="../js/bootstrap.bunble.js"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>