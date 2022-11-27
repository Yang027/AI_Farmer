<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml">
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
session_start();
// $email = $_SESSION['email'];
// $query = "SELECT * FROM google_users WHERE email ='$email'"; //搜尋 *(全部欄位) ，從 表staff
// $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
// if ($query_run) {
// 	// echo mysqli_num_rows($query_run);
// 	if (mysqli_num_rows($query_run) > 0) {
// 		while ($row = mysqli_fetch_assoc($query_run)) {
// 			// 每跑一次迴圈就抓一筆值，最後放進data陣列中
// 			$name = $row['username'];
// 		}
// 	}
// 	mysqli_free_result($query_run);
// }
$name = "abner_yang";
$_SESSION['email'] = 'a108222027@mail.shu.edu.tw';
$email = $_SESSION['email'];
?>

<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> -->
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>溫室畫面</title>
    
	<link rel="stylesheet" type="text/css" href="../css/circle1.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	</script>
	<link href="https://cdn.jsdelivr.net/npm/round-slider@1.6.1/dist/roundslider.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/round-slider@1.6.1/dist/roundslider.min.js"></script>

	<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="https://kit.fontawesome.com/0e12d05167.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/round-slider@1.6.1/dist/roundslider.min.css" rel="stylesheet" />

	<script src="https://cdn.jsdelivr.net/npm/round-slider@1.6.1/dist/roundslider.min.js"></script>

    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
    <style>
       
        .flag {
            margin-top: 2%;
            margin-right: 1%;
            border-radius: 6px;
            width: 20%;

            box-shadow: 0 10px 20px #777;
            padding: 2px;
            height: 15%;
            margin-left: 0.5%;
            float: right;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            background: white;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -moz-osx-font-smoothing: grayscale;
            -webkit-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-property: box-shadow, transform;
            transition-property: box-shadow, transform;
        }
        .imgee {
            width: 300px;
            margin: 0 auto;
        }

        /* div img {
            display: block;
            float: left;
        } */

        /*END Form Wizard*/
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.js"></script>

    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	https://unpkg.com/mqtt/dist/mqtt.js  can't not use anymore -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F2DCBB;">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #1D3124;">
                            知識庫
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="../general/fruit.php">水果</a></li>
                            <li><a class="dropdown-item" href="../general/fruit.php">蔬菜</a></li>
                            <li><a class="dropdown-item" href="../general/fruit.php">花卉</a></li>
                            <li><a class="dropdown-item" href="../general/fruit.php">其他</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #1D3124;">
                            病蟲害知識庫
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="../general/pest_search.php">蟲害查詢</a></li>
                            <li><a class="dropdown-item" href="../general/disease_search.php">病害查詢</a></li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
						<a class="nav-link" href="greenhouse_main.php" style="color: #1D3124;">溫室環控</a>
					</li> -->
                </ul>
                <?php
                if (isset($_SESSION['email'])) {
                    //$email =  $_SESSION['email'];
                    echo '<input type="button" class="btn btn-outline-secondary" value="登出" onclick="location.href=\'' . '../googlelogout.php\'' . '">';
                } else {
                    echo '<input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = \'' . $login_url . '\'">';
                }
                ?>
                <!-- ?<input type="button" id='login' class="btn btn-link" style='outline:none' value="<?php echo $name; ?>" onclick="location.href='login.html'"> -->
            </div>
        </div>
    </nav>

    <div class="wrapper d-flex align-items-stretch">

        <!-- session或是什麽判斷一下是否有成功登錄 -->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 13% ">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">HI,<?php echo $name; ?></span>
            </a>
            <hr>
            <br>
            <ul class="nav nav-pills flex-column mb-auto">
                <li style="font-size:18px">
                    <a href="greenhouse_main.php" class="nav-link text-white" aria-current="page" style="text-align:  center;">
                        <img src="../images/home-16.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
                        <span>我的溫室</span>
                    </a>
                </li>

                <li class="nav-item" style="font-size:18px">
                    <a href="greenhouse_control.php" class="nav-link text-white" style="text-align:  center;">
                        <!-- <a href="greenhouse_control.php" class="nav-link text-white" style="text-align:  center;"> -->
                        <img src="../images/webcam-2-16.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
                        <span> 溫室監控</span>
                    </a>
                </li>
                <li class="nav-item" style="font-size:18px">
                    <a href="camera.php" class="nav-link text-white active" style="text-align: center;">
                        <img src="../images/camera.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
                        <span> 溫室畫面</span>
                    </a>
                </li>

                <br>
            </ul>
        </div>
        <div class="flag" style="display:flex;flex-direction:column;align-items:center;">
            <p style="font-size:25px ;float:left">👉👉電腦登錄👈👈</p>
            <!-- <a href='https://ai.shu.edu.tw/GTopic2022/AI_Farmer/greenhouse_control/line.html?mail=<?php echo $email ?>'>line</a> -->
            <a href='https://ai.shu.edu.tw/GTopic2022/AI_Farmer/greenhouse_control/line.html?mail=<?php echo $email ?>'>Line Notify 異常狀況提醒</a>
            <p style="font-size:25px ;">👉👉手機登錄👈👈</p>
            <?php echo '<img style="width: 70%;height: 70%;" src="https://api.qrserver.com/v1/create-qr-code?data=https://ai.shu.edu.tw/GTopic2022/AI_Farmer/greenhouse_control/line.html?mail=' . $email . '"/>'; ?>
        </div>
        <div class="container overflow-hidden text-center imgee">
            <div class="row gx-5">
                <div class="col">

                    <img src="../images/gh_01.png" class="rounded float-start imgee" alt="..." onclick="location.href='http://192.168.0.112/'">

                </div>
                <div class="col">
                    <img src="../images/gh_02.png" class="rounded float-start imgee" alt="..." onclick="location.href='http://192.168.0.160/'">
                </div>
            </div>
        </div>

        <!-- <div style="width:800px;height:600px">
                <img style="width:400px;height:600px" src="../images/gh_01.png" />
                <img style="width:400px;height:600px" src="../images/gh_02.png" />
            </div> -->


        </table>

</body>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/bootstrap.bunble.js"></script>
<script src="../js/bootstrap.js"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</html>