<?php
require_once '../config.php';
require_once "../control.php";
?>

<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <?php $name = $_GET['item']; ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="../css/pest_search.css"> -->
    <title><?php echo $name; ?></title>
    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .cen {
            text-align: center
        }
    </style>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../homepage_logout.php">
                <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                病害查詢
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
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">聯絡我們</a>
                        <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            聯絡我們
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="contact.php">尋求協助</a></li>
                            <li><a class="dropdown-item" href="suggest.php">給予建議</a></li>
                        </ul>
                    </li> -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location = '<?php echo $login_url; ?>'">溫室環控</a>
                    </li>
                    <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <?php

    $name = $_GET['item'];
    // $server_name = '192.192.156.30';
    // $username = 'aifarmer';
    // $password = '1234';
    // $db_name = 'aifarmer';

    // // mysqli 的四個參數分別為：伺服器名稱、帳號、密碼、資料庫名稱
    // $conn = new mysqli($server_name, $username, $password, $db_name);
    include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
    $con = mysqli_connect($db_host, $db_username, $db_password, $database);

    if (!empty($con->connect_error)) {
        die('資料庫連線錯誤:' . $con->connect_error);    // die()：終止程序
    }

    $query = "SELECT * FROM disease WHERE disease='$name'"; //搜尋 *(全部欄位) ，從 表staff
    $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
    $sql = "SELECT * FROM disease_plant WHERE disease='$name'";
    $sql_run = mysqli_query($con, $sql);

    if ($query_run) {
        // echo mysqli_num_rows($query_run);
        if (mysqli_num_rows($query_run) > 0) {
            while ($row = mysqli_fetch_assoc($query_run)) {
                // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                $img = $row['image'];
                $link = $row['resource'];
                $intro = $row['introduction'];
                $feature = $row['feature'];
                $address = $row['address'];
                $period = $row['period'];
                $preventive = $row['preventive_period'];
                $eviction = $row['eviction_period'];
                $predict = $row['predict_policy'];
                $solution = $row['solution'];
            }
        }
        mysqli_free_result($query_run);
    }
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../homepage_logout.php">
                    <font color="#7F5F3F">
                        Home
                    </font>
                </a></li>
            <li class="breadcrumb-item"><a href="disease_search.php">
                    <font color="#7F5F3F">
                        病害知識庫
                    </font>
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <font color="#7F5F3F">
                    <?php echo $name; ?>
                </font>
            </li>
        </ol>
    </nav>
    <div class="justify-content-center">
        <div class="row g-3 justify-content-center">
            <div class="col-2"></div>
            <div class="col-8" style="background-color: #DCD4CB;">
                <h2 class="text-center"><?php
                                        echo $name;
                                        echo "<br>"; ?></h2>
                <?php echo '<img src="' . $img . '"  height="300" class="d-block w-50 center" alt="...">' ?>
                <p class="cen">照片來源：<?php echo $link; ?></p>
                <h4 id="scrollspyHeading1">病害簡介</h4>
                <p><?php echo $intro; ?></p>
                <h4 id="scrollspyHeading1">病害特徵</h4>
                <p><?php echo $feature; ?></p>
                <h4 id="scrollspyHeading1">發病部位</h4>
                <p><?php echo $address; ?></p>
                <h4 id="scrollspyHeading1">淺伏時期</h4>
                <p><?php echo $period; ?></p>
                <h4 id="scrollspyHeading1">預防時期</h4>
                <p><?php echo $preventive; ?></p>
                <h4 id="scrollspyHeading1">驅逐時期</h4>
                <p><?php echo $eviction; ?></p>
                <h4 id="scrollspyHeading1">預防方式</h4>
                <p><?php echo $predict; ?></p>
                <h4 id="scrollspyHeading1">解決方法</h4>
                <p><?php echo $solution; ?></p>
                <?php
                if ($sql_run) {
                    $p = 0;
                    // echo mysqli_num_rows($query_run);
                    if (mysqli_num_rows($sql_run) > 0) {

                        while ($row = mysqli_fetch_assoc($sql_run)) {
                            // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                            $p++;
                            $plant[$p] = $row['plant_id'];
                        }
                    }
                    mysqli_free_result($sql_run);
                }
                ?>
                <h4 id="scrollspyHeading1">常見作物</h4>
                <p><?php
                    foreach ($plant as $p) {
                        echo $p . "\n";
                    } ?></p>
            </div>
            <div class="col-2"></div>
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