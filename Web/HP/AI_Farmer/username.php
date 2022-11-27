<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<?php
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_DEPRECATED);
?>

<head>
    <?php
    session_start();
    // echo $_SESSION['id'];
    // echo $_SESSION['email'];
    $id =  $_SESSION['id'];
    $email =  $_SESSION['email'];
    ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title>註冊</title>
    <style>
        h1 {
            text-align: center;
        }
    </style>

</head>

<body>
    <h1><img src="images/logo.jpg" alt="" width="36" height="30" class="d-inline-block align-text-center">HI 家人們ヽ(#`Д´)ﾉ</h1>
    <?php
    include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php

    $con = mysqli_connect("$db_host", "$db_username", "$db_password", "$database");
    $query = "SELECT * FROM google_user WHERE id='$id'"; //搜尋 *(全部欄位) ，從 表staff
    // echo "<b>SQL指令: $query</b><br/>";
    $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php

    if (isset($_POST['Insert'])) {
        // 開啟MySQL的資料庫連接
        $link = mysqli_connect("$db_host", "$db_username", "$db_password", "$database")
            or die("無法開啟MySQL資料庫連接!<br/>");
        mysqli_select_db($link, "aifarmer");  // 選擇資料庫
        // 建立新增記錄的SQL指令字串
        $sql = "SELECT * FROM google_users WHERE username = '" . $_POST["passward"] . "'";
        $query_runn = mysqli_query($link, $sql);
    }
    ?>
    <form action="username.php" method="post">

        <div class="mb-3">
            <label for="exampleInputUserID1" class="form-label">使用者名稱</label>
            <input type="text" name="passward" class="form-control" id="exampleInputUserID1">
            <?php
            if (mysqli_num_rows($query_runn) > 0) {
                echo "username已被使用,請重新輸入。";
            } else {
                $aaa = "INSERT INTO google_users (email, id, username) VALUES('" . $email . "','" . $id . "','" . $_POST["passward"] . "') ON DUPLICATE KEY UPDATE username= '" . $_POST["passward"] . "'";
                if (mysqli_query($link, $aaa)) {
                    header('Location: greenhouse_control/greenhouse_control.php');
                }
                mysqli_close($link);      // 關閉資料庫連接
                // header('Location: loginpage.php');
                // header('Location: greenhouse_control/bpgreenhouse_control.php');
            }
            // $_SESSION['id'] =  $passward;
            // echo $_SESSION['password'];
            // $password =  $_SESSION['password'];
            ?>
        </div>

        <button type="submit" name="Insert" class="btn btn-primary" style="background-color: #795e42; border:1px #795e42 solid" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            確認
        </button>
        <?php
        // echo $_SESSION['password'];
        // $password =  $_SESSION['password'];
        ?>
    </form>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.bunble.js"></script>
    <script src="js/bootstrap.js"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>