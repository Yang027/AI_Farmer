<?php session_start(); ?>

<?php
require_once '../config.php';
require_once "../control.php";
?>

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
            <a class="navbar-brand" href="../homepage_logout.php" style="color: #763c16;">
                <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                更多花卉資訊
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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../homepage_logout.php">
                    <font color="#7F5F3F">
                        Home
                    </font>
                </a></li>
            <li class="breadcrumb-item"><a href="flower.php">
                    <font color="#7F5F3F">
                        花卉知識庫/
                    </font>
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <font color="#7F5F3F">
                    <?php echo $name; ?>
                </font>
            </li>
        </ol>
    </nav>
    <?php

    $name = $_GET['item'];
    include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
    $con = mysqli_connect($db_host, $db_username, $db_password, $database);

    if (!empty($con->connect_error)) {
        die('資料庫連線錯誤:' . $con->connect_error);    // die()：終止程序
    }

    $query = "SELECT * FROM plant WHERE plant_id='$name'"; //搜尋 *(全部欄位) ，從 表staff
    $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php


    if ($query_run) {
        // echo mysqli_num_rows($query_run);
        if (mysqli_num_rows($query_run) > 0) {
            while ($row = mysqli_fetch_assoc($query_run)) {
                // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                $image = $row['image'];
                $link = $row['link'];
                $introduction = $row['introduction'];
                $feature = $row['feature'];
                $variety = $row['variety'];
                $seeding = $row['seeding'];
                $feild_planting = $row['feild_planting'];
                $harvest = $row['harvest'];
                $precautions = $row['precautions'];
                $command_isseu = $row['command_isseu'];
            }
        }
        mysqli_free_result($query_run);
    }
    ?>
    <!-- <nav aria-label="breadcrumb">
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
    </nav> -->



    <div class="container-fuild">
        <div class="row">
            <div class="col-2">
            </div>
            <div class="col-8" style="background-color: #DCD4CB;">
                <!-- <img src="../images/1.jpg" class="rounded mx-auto d-block" alt="..." height="300"> -->
                <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-offset="0" tabindex="0">
                    <h2 class="text-center"><?php
                                            echo $name;
                                            echo "<br>"; ?></h2>
                    <?php echo '<img src="' . $image . '"  height="300" class="d-block w-50 center" alt="...">' ?>
                    <p class="cen">照片來源：<?php echo $link; ?></p>
                    <h4 id="item-1">簡介</h4>
                    <p>
                    <p><?php echo $introduction; ?></p>
                    </p>
                    <h4 id="item-2">栽培特性</h4>
                    <p><?php echo $feature; ?></p>
                    <h4 id="item-3">品種</h4>
                    <p><?php echo $variety; ?></p>
                    <h4 id="item-4">種植資訊</h4>
                    <h5 id="item-4-1">撒種</h5>
                    <p><?php echo $seeding; ?></p>
                    <h5 id="item-4-2">定植</h5>
                    <p><?php echo $feild_planting; ?></p>
                    <h5 id="item-4-3">收穫</h5>
                    <p><?php echo $harvest; ?></p>
                    <h5 id="item-5">注意事項</h5>
                    <p><?php echo $precautions; ?></p>
                </div>
                <h4 id="item-6">Q&A</h4>
                <p><?php echo $command_isseu; ?></p>

            </div>

            <div class="col-8">
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