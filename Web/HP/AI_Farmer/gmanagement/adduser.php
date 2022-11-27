<?php session_start(); ?>
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_DEPRECATED);

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
    <title>新增溫室</title>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(248, 195, 195);">
        <div class="container-fluid">
            <a class="navbar-brand" href="../homepage_logout.php">
                <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                新增 溫室 ᕦ( ᐛ )ᕡ

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../homepage_logout.php" style="color: #1D3124;">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" font-weight="bold;">
                            新增
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="pest_insert.php">害蟲</a></li>
                            <li><a class="dropdown-item" href="disease_insert.php">病害</a></li>
                            <li><a class="dropdown-item" href="plant_insert.php">植物</a></li>
                            <li><a class="dropdown-item" href="pests_plant_insert.php">害蟲影響植物</a></li>
                            <li><a class="dropdown-item" href="disease_plant_insert.php">病害影響植物</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            刪除
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <!-- <li><a class="dropdown-item" href="pest_delete.php">害蟲</a></li>
              <li><a class="dropdown-item" href="disease_delete.php">病害</a></li>
              <li><a class="dropdown-item" href="plant_delete.php">植物</a></li> -->
                            <li><a class="dropdown-item" href="pests_plant_delete.php">害蟲影響植物</a></li>
                            <li><a class="dropdown-item" href="disease_plant_delete.php">病害影響植物</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            修改
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="pest_update.php">害蟲</a></li>
                            <li><a class="dropdown-item" href="disease_update.php">病害</a></li>
                            <li><a class="dropdown-item" href="plant_update.php">植物</a></li>
                            <!-- <li><a class="dropdown-item" href="pests_plant_update.php">害蟲影響植物</a></li>
              <li><a class="dropdown-item" href="disease_plant_update.php">病害影響植物</a></li> -->
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location = '<?php echo $login_url; ?>'">溫室</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">新增溫室</a>
                    </li>
                    <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <?php
    $con = mysqli_connect($db_host, $db_username, $db_password, $database)
        or die("無法開啟MySQL資料庫連接!<br/>");
    mysqli_select_db($con, $database);  // 選擇資料庫
    // 建立新增記錄的SQL指令字串
    $sql =  "SELECT username FROM `google_users`";
    $result = mysqli_query($con, $sql);
    ?>
    <form action="adduser.php" method="post">
        </br>
        <div class="row mb-3 justify-content-md-center">
            <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">使用者選擇</label>
            <div class="col-sm-6">
                <select class="form-select" aria-label="Default select example" name="currgh">
                    <option selected>--請選擇欲加入的使用者--</option>
                    <?php

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                                if (implode($row) == $nowgh) {
                                    echo "<option value=" . implode($row) . " selected>" . implode($row) . "</option>";
                                    $flag = true;
                                } else
                                    echo "<option value=" . implode($row) . " >" . implode($row) . "</option>";
                            }
                        }
                        mysqli_free_result($result);
                    }

                    ?>
                </select>
            </div>
        </div>

        <div class="d-md-flex justify-content-md-center">
            <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Add" style="background-color: #FEDFE1;">新增溫室</button>
        </div>
    </form>


    <?php
    // 是否是表單送回
    if (isset($_POST["Add"])) {
        // 開啟MySQL的資料庫連接
        $link = mysqli_connect($db_host, $db_username, $db_password, $database)
            or die("無法開啟MySQL資料庫連接!<br/>");
        mysqli_select_db($link, $database);  // 選擇資料庫
        // 建立更新記錄的SQL指令字串

        // select 想收尋的欄位(資料表A & B都可) from [資料表A] inner Join [資料表B] on [資料表A].[關聯鍵] =[資料表B].[關聯鍵]
        $sql = "SELECT greenhouse_ID from usergreenhouse inner Join google_users on usergreenhouse.mail = google_users.email WHERE username ='" . $_POST["currgh"] . "'";


        // echo "<b>SQL指令: $sql</b><br/>";
        //送出UTF8編碼的MySQL指令
        mysqli_query($link, 'SET NAMES utf8');
        // $result2=mysqli_query($link, $sql);
        if (mysqli_query($link, $sql)) // 執行SQL指令
        {
            if (mysqli_num_rows(mysqli_query($link, $sql)) > 0) {

                // echo mysqli_affected_rows($link);
                $num = mysqli_affected_rows($link);
                $num += 1;
                // echo "num" . $num;
                mysqli_close($link);      // 關閉資料庫連接


                include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php

                $con = mysqli_connect($db_host, $db_username, $db_password, $database);


                if (!empty($con->connect_error)) {
                    die('資料庫連線錯誤:' . $con->connect_error);    // die()：終止程序
                }


                $query = "SELECT * FROM google_users WHERE username ='" . $_POST["currgh"] . "'"; //搜尋 *(全部欄位) ，從 表staff
                $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php


                if ($query_run) {
                    // echo mysqli_num_rows($query_run);
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                            $mail = $row['email'];
                        }
                    }
                    mysqli_free_result($query_run);
                }
                // echo $mail;
                $tmp= ($num > 9)? strval($num) : ("0" . strval($num));
               
                $sql2 = "INSERT INTO usergreenhouse (greenhouse_ID, mail) VALUES ('gh_" . $_POST["currgh"] . "_" .$tmp . "','" . $mail . "')";
                echo ($sql2);
                $query_run2 = mysqli_query($con, $sql2); //$con <<此變數來自引入的 db_cn.php
                // echo $sql2;
            } else {
                $query = "SELECT * FROM google_users WHERE username ='" . $_POST["currgh"] . "'"; //搜尋 *(全部欄位) ，從 表staff
                $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php


                if ($query_run) {
                    // echo mysqli_num_rows($query_run);
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                            $mail = $row['email'];
                        }
                    }
                    mysqli_free_result($query_run);
                }
                // echo $mail;
                $sql2 = "INSERT INTO usergreenhouse (greenhouse_ID, mail) VALUES ('gh_" . $_POST["currgh"] . "_01','" . $mail . "')";
                $query_run2 = mysqli_query($con, $sql2); //$con <<此變數來自引入的 db_cn.php
                echo ($sql2);
            }



            echo "<script type='text/javascript'>alert('資料庫更新記錄成功!');</script>";
        } else
            // die("資料庫更新記錄失敗<br/>");
            echo "<script type='text/javascript'>alert('資料庫更新記錄失敗!');</script>";
        mysqli_close($link);      // 關閉資料庫連接
    }
    ?>

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