<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<?php
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_DEPRECATED);
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect("192.192.156.30", $db_username, $db_password, $database);
session_start();
$email =  $_SESSION['email'];
$query = "SELECT * FROM google_users WHERE email ='$email'"; //搜尋 *(全部欄位) ，從 表staff
$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
if ($query_run) {
	// echo mysqli_num_rows($query_run);
	if (mysqli_num_rows($query_run) > 0) {
		while ($row = mysqli_fetch_assoc($query_run)) {
			// 每跑一次迴圈就抓一筆值，最後放進data陣列中
			$name = $row['username'];
		}
	}
	mysqli_free_result($query_run);
}
// $name = "abner_yang";
// $_SESSION['email'] = 'a108222027@mail.shu.edu.tw'
?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<title>溫室環控</title>
	<!-- Google Font Roboto -->
	<link rel="stylesheet" type="text/css" href="../css/circle.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

	<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
	<style>
		.title-container {
			z-index: 999999;
			text-align: center;
			display: flex;
			flex-direction: column;
			justify-content: center;
			position: fixed;
			width: 40%;

			left: 50%;
			top: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.spinner {
			display: flex;
			justify-content: center;
		}

		.simg {
			width: 50%;
			max-width: 80rem;
			/*	max-width: 800px;*/
			animation: rotate 3.6s linear infinite;
		}

		.rcircle {
			fill: none;
			stroke: #000080;
			stroke-width: 8px;
			stroke-dasharray: 300;
			animation: outline 2s cubic-bezier(0.77, 0, 0.18, 1) infinite;
		}

		@keyframes outline {
			0% {
				stroke-dashoffset: 0;
			}

			50% {
				stroke-dashoffset: 300;
			}

			100% {
				stroke-dashoffset: 600;
			}
		}

		@keyframes rotate {
			from {
				transform: rotate(0turn);
			}

			to {
				transform: rotate(-1turn);
			}
		}

		.radio-btn {
			display: none;
		}

		.platform-choosing {
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
		}

		.platform-choosing .radio-group {
			margin-top: 0.5rem;
			width: 16rem;
			display: flex;
			justify-content: space-between;
		}

		.platform-choosing .radio-group a {
			width: 50%;
			height: 100%;
			background-color: white;
			padding: 1rem 0;
			text-align: center;
			color: black;
			border: solid 1px gray;
			text-decoration: none;
			transition: 0.3s;
		}

		#area-chart,
		#line-chart,
		#bar-chart,
		#stacked,
		#pie-chart {
			min-height: 250px;
		}

		.bs-wizard {
			margin-top: 40px;
		}

		/*Form Wizard*/
		.bs-wizard {
			border-bottom: solid 1px #e0e0e0;
			padding: 0 0 10px 0;
		}

		.bs-wizard>.bs-wizard-step {
			padding: 0;
			position: relative;
		}

		.bs-wizard>.bs-wizard-step .bs-wizard-stepnum {
			color: #595959;
			font-size: 16px;
			margin-bottom: 5px;
		}

		.bs-wizard>.bs-wizard-step .bs-wizard-info {
			color: #999;
			font-size: 14px;
		}

		.bs-wizard>.bs-wizard-step>.bs-wizard-dot {
			position: absolute;
			width: 30px;
			height: 30px;
			display: block;
			background: #fbe8aa;
			top: 45px;
			left: 50%;
			margin-top: -15px;
			margin-left: -15px;
			border-radius: 50%;
		}

		.bs-wizard>.bs-wizard-step>.bs-wizard-dot:after {
			content: ' ';
			width: 14px;
			height: 14px;
			background: #fbbd19;
			border-radius: 50px;
			position: absolute;
			top: 8px;
			left: 8px;
		}

		.bs-wizard>.bs-wizard-step>.progress {
			position: relative;
			border-radius: 0px;
			height: 8px;
			box-shadow: none;
			margin: 20px 0;
		}

		.bs-wizard>.bs-wizard-step>.progress>.progress-bar {
			width: 0px;
			box-shadow: none;
			background: #fbe8aa;
		}

		.bs-wizard>.bs-wizard-step.complete>.progress>.progress-bar {
			width: 100%;
		}

		.bs-wizard>.bs-wizard-step.active>.progress>.progress-bar {
			width: 50%;
		}

		.bs-wizard>.bs-wizard-step:first-child.active>.progress>.progress-bar {
			width: 0%;
		}

		.bs-wizard>.bs-wizard-step:last-child.active>.progress>.progress-bar {
			width: 100%;
		}

		.bs-wizard>.bs-wizard-step.disabled>.bs-wizard-dot {
			background-color: #cfcaca;
		}

		.bs-wizard>.bs-wizard-step.disabled>.bs-wizard-dot:after {
			opacity: 0;
		}

		.bs-wizard>.bs-wizard-step:first-child>.progress {
			left: 50%;
			width: 50%;
		}

		.bs-wizard>.bs-wizard-step:last-child>.progress {
			width: 50%;
		}

		.bs-wizard>.bs-wizard-step.disabled a.bs-wizard-dot {
			pointer-events: none;
		}

		/*END Form Wizard*/
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.js"></script>

	<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->

	<!--https://unpkg.com/mqtt/dist/mqtt.js  can't not use anymore -->
</head>
<?php
//$selected = false;
if (isset($_GET["currgh"])) {
	$nowgh = $_GET["currgh"]; //讀option value
	echo "<script>var nowgh='" . $nowgh . "';</script>";
}


?>

<body onload='mqttconnect()'>
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
				<!-- <input type="button" id='login' class="btn btn-link" style='outline:none' value="<?php echo $name; ?>" onclick="location.href='login.html'"> -->
				<?php
				if (isset($_SESSION['email'])) {
					//$email =  $_SESSION['email'];
					echo '<input type="button" class="btn btn-outline-secondary" value="登出" onclick="location.href=\'' . '../googlelogout.php\'' . '">';
				} else {
					echo '<input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = \'' . $login_url . '\'">';
				}
				?>
			</div>
		</div>
	</nav>


	<div class="wrapper d-flex align-items-stretch">

		<!-- session或是什麽判斷一下是否有成功登錄 -->
		<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 13%; ">
			<a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
				<span class="fs-4">HI,<?php echo $name; ?></span>
			</a>
			<hr>
			<br>
			<ul class="nav nav-pills flex-column mb-auto">
				<li style="font-size:18px">
					<a href="greenhouse_main.php" class="nav-link active" aria-current="page" style="text-align:  center;">
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
				<li style="font-size:18px">
					<a href="camera.php" class="nav-link text-white" style="text-align: center;">
						<img src="../images/camera.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
						<span> 溫室畫面</span>
					</a>
				</li>
				<!-- <li style="font-size:20px">
					<a href="greenhouse_setting.html" class="nav-link text-white" style="text-align: center;">
						<img src="../images/cog-16.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
						<span> 設定</span>
					</a>
				</li> -->
				<br>
			</ul>
		</div>
		<div class="p-4 p-md-5" style="width:85%;">
			<!--here-->
			<?php
			include('../sqlconnect.php');

			// $greenhouse = array();
			// sql語法存在變數中
			$sql = 'SELECT `greenhouse_ID` FROM `usergreenhouse` INNER JOIN `google_users` ON
			`usergreenhouse`.mail=`google_users`.email WHERE `username`="' . $name . '"'; //{abner_yang}要換成現在使用者的username
			// 用mysqli_query方法執行(sql語法)將結果存在變數中
			$result = mysqli_query($con, $sql);

			echo '<form method="GET" action="">';
			echo '<h3 style="font-size:20px;margin-top:20px">溫室選擇';
			echo '<select name="currgh" style="float:right" onchange="this.form.submit()">';
			echo '<option value="" disabled selected> --請選擇你欲查看的溫室-- </option>';

			if (isset($_GET["currgh"])) {
				$nowgh = $_GET["currgh"]; //讀option value
			}
			if ($result) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						// 每跑一次迴圈就抓一筆值，最後放進data陣列中
						if (implode($row) == $nowgh)
							echo "<option value=" . implode($row) . " selected>" . implode($row) . "</option>";
						else
							echo "<option value=" . implode($row) . ">" . implode($row) . "</option>";
					}
				}
				mysqli_free_result($result);
			} else {
				echo "<option value=''>" . '您目前無溫室' . "</option>";
				//動作
			}
			echo '</select>';
			echo '</span>';
			echo '</form>';

			// sql語法存在變數中
			$co2 = NULL;
			$temp = NULL;
			$humid = NULL;
			$soil_humid = NULL;
			$lux = NULL;
			if (isset($_GET["currgh"])) {
				//抓最新的植物
				$nowgh = $_GET["currgh"]; //讀option value
				$sql = 'SELECT `start_time` FROM `greenhouse_plant`
			WHERE `greenhouse_id`="' . $nowgh . '" and `end_time` =0';
				//{abner_yang}要換成現在使用者的username
				// 用mysqli_query方法執行(sql語法)將結果存在變數中
				$result = mysqli_query($con, $sql);
				$starttime = mysqli_num_rows($result) > 0 ? implode(mysqli_fetch_assoc($result)) : null;
				//var_dump($starttime);
				//var_dump($nowgh);
				if ($starttime != null) {
					$sql = 'SELECT `co2`,`temperature`,`humidity` FROM `air_sensor_data` as a
			WHERE `greenhouse_id`="' . $nowgh . '" and a.start_time=' . $starttime .
						' Order BY a.record_time DESC';
					//{abner_yang}要換成現在使用者的username
					// 用mysqli_query方法執行(sql語法)將結果存在變數中
					$result = mysqli_query($con, $sql);
					// var_dump($sql);
					if ($result) {
						if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);
							// 每跑一次迴圈就抓一筆值，最後放進data陣列中
							$co2 = $row['co2'];
							$temp = $row['temperature'];
							$humid = $row['humidity'];
							// $lux = $row['accumulation_lux'];
						}
						mysqli_free_result($result);
					}
					$sql = 'SELECT `accumulation_lux` FROM `lux_sensor_data` as a
			WHERE a.`greenhouse_id`="' . $nowgh . '" and a.start_time=' . $starttime .
						' Order BY a.record_time DESC';
					//{abner_yang}要換成現在使用者的username
					// 用mysqli_query方法執行(sql語法)將結果存在變數中
					// var_dump($sql);
					$result = mysqli_query($con, $sql);

					if ($result) {
						if (mysqli_num_rows($result) > 0) {

							$row = mysqli_fetch_assoc($result);
							// var_dump($row);
							$lux = $row['accumulation_lux'];
						}
						mysqli_free_result($result);
					}

					$sql = 'SELECT `greenhouse_soil`.`soil_sensor_id` FROM `greenhouse_soil` WHERE `greenhouse_id`="' . $nowgh . '" AND start_time =' . $starttime;
					$result = mysqli_query($con, $sql);
					$ii = 0;
					//var_dump($sql);
					$soilsensor = array();
					$soil = array();
					if ($result) {
						$soil_count = mysqli_num_rows($result);
						//var_dump($soil_count);
						if (mysqli_num_rows($result) > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								$sid = implode($row);
								//var_dump($sid);
								$sql = 'SELECT `soil_humidity` FROM `soil_sensor_data`
			WHERE sensor_id="' . $sid . '" ORDER BY `record_time` DESC';
								//var_dump($sql);
								$soilresult = mysqli_query($con, $sql);
								$soilsensor[$ii] = implode(mysqli_fetch_assoc($soilresult));
								$soil[$ii] = $sid;
								//var_dump($soilresult);
								// var_dump(soilsensor);
								$ii += 1;
							}
							mysqli_free_result($result);
						}
					}
					$ii = 0;
					$soil_humid = $soilsensor[$ii];
				}
			}
			?>
			<h3 style="font-size:20px;margin-top:20px">溫室數據總覽</h3>
			<hr>
			<div class="flag" style='cursor:pointer' onclick="histdata('co2')">
				<h4>CO2</h4>
				<?php
				if ($co2 == NULL) {
					echo '<h4 style="font-size:18px">';
					echo '無資料</h4> ';
				} else {
					echo '<h4 style="font-size:40px" class="co2">';
					echo $co2 . '(ppm)</h4> ';
					echo '<div class="img_out">';
					echo '<img src="../images/photosynthesis.png" alt="" width="120" height="80" /></div>';
				}
				?>
			</div>
			<div class="flag" style='cursor:pointer' onclick="histdata('temp')">
				<h4>temperature</h4>
				<?php
				if ($temp == NULL) {
					echo '<h4 style="font-size:18px">';
					echo '無資料</h4> ';
				} else {
					echo '<h4 style="font-size:40px"  class="temp">';
					echo $temp . '°C</h4> ';
					echo '<div class="img_out">';
					echo '<img src="../images/temperature-2.png" alt="" width="100" height="80" /></div>';
				}
				?>
			</div>
			<div class="flag" style='cursor:pointer' onclick="histdata('humid')">
				<h4>humidity</h4>
				<?php
				if ($humid == NULL) {
					echo '<h4 style="font-size:18px" >';
					echo '無資料</h4> ';
				} else {
					echo '<h4 style="font-size:40px" class="humid">';
					echo $humid  . '%</h4> ';
					echo '<div class="img_out">';
					echo '<img src="../images/pot.png" alt=""  width="90" height="80"/></div>';
				}
				?>
			</div>
			<div class="flag" style='cursor:pointer' onclick="histdata('soil_humid')">
				<h4>soil_humidity</h4>
				<?php
				if ($soil_humid == NULL) {

					echo '<h4 style="font-size:18px">';
					echo '無資料</h4> ';
				} else {
					if (isset($_GET['soilpage'])) {

						$page = $_GET['soilpage'];
						if ($page > 0) {
							echo '<a href="greenhouse_main.php? currgh=' . $nowgh . '&soilpage=' . ($page - 1) . '" class="arrow left"></a>';
						}
						if ($page + 1 < $soil_count) {
							echo '<a href="greenhouse_main.php?currgh=' . $nowgh . '&soilpage=' . ($page + 1) . '" class="arrow right"></a>';
						}
						if ($page > $soil_count) {
							echo '<script>location.href="greenhouse_main.php?currgh=' . $nowgh . '"</script>';
						}
						echo '<h2 style="font-size:30px;float:right">#' . ($page + 1) . '</h2>';
						echo '<h4 style="font-size:40px" class="soil">';
						echo $soilsensor[$page] . '%</h4> ';
						echo '<div class="img_out">';
						echo '<img src="../images/plant.png" alt="" width="100" height="80"/></div>';
					} else {

						if ($soil_count > 1) {
							echo '<a href="greenhouse_main.php?currgh=' . $nowgh . '&soilpage=' . ($ii + 1) . '" class="arrow right"></a>';
						}
						echo '<h2 style="font-size:30px;float:right;color: #11324D;">#' . ($ii + 1) . '</h2>';
						echo '<h4 style="font-size:40px " class="soil">';
						echo $soilsensor[$ii] . '%</h4> ';
						// $_SESSION['soilid']=$soilsensor[$ii];
						echo '<div class="img_out">';
						echo '<img src="../images/plant.png" alt="" width="100" height="80"/></div>';
					}
				}
				?>
			</div>
			<div class="flag" style='cursor:pointer' onclick="histdata('lux')">
				<h4>accumulation_lux</h4>
				<?php
				if ($lux == NULL) {
					echo '<h4 style="font-size:18px" class="lux">';
					echo '無資料</h4> ';
				} else {
					echo '<h4 style="font-size:40px" class="lux">';
					echo $lux . '(lux)</h4> ';
					echo '<div class="img_out">';
					echo '<img src="../images/plants-2.png" alt="" width="90" height="80"/></div>';
				}
				?>
			</div>
			<div class="box">
				<h3 style="font-size:25px;margin-top:2%;text-align:center">當前周期</h3>
				<?php
				// 幼苗期   成長期   開花期  結果期 => 1->2->3->4
				//GrowCycle::開花期->value
				if (isset($nowgh)) {
					$cycle = 'SELECT growth_cycle FROM `growth_cycle` WHERE `greenhouse_id`="' . $nowgh . '" and start_time=' . $starttime . ' ORDER BY `record_time` DESC';
					$result = mysqli_query($con, $cycle);
					$currcycle = 1;
					if ($result) {
						if (mysqli_num_rows($result) > 0) {
							$currcycle = implode(mysqli_fetch_assoc($result));
							//var_dump($currcycle);
						}
						mysqli_free_result($result);
					}
				} else $currcycle = 1;
				?>
				<div class="container">
					<div class="title-container" id='title-container' style="display:none;">
						<div class="spinner">
							<svg class='simg' id='simg' viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
								<circle class='rcircle' id='rcircle' cx="50" cy="50" r="46" />
							</svg>
						</div>
						<h1 class="title">加載歷史資料中...</h1>
					</div>
					<div class="row bs-wizard" style="border-bottom:0;">
						<?php
						$count = 1;
						while ($count < $currcycle) {
							echo '	<div class="col-md-3 bs-wizard-step complete">';
							echo '	<div class="text-center bs-wizard-stepnum">Step ' . $count . '</div>';
							echo '	<div class="progress">';
							echo '	<div class="progress-bar"></div>';
							echo '	</div>';
							echo '	<a href="#" class="bs-wizard-dot"></a>';
							switch ($count) {
								case 1:
									echo '<div class="bs-wizard-info text-center">幼苗期</div>';
									break;
								case 2:
									echo '<div class="bs-wizard-info text-center">成長期</div>';
									break;
								case 3:
									echo '<div class="bs-wizard-info text-center">開花期</div>';
									break;
								case 4:
									echo '<div class="bs-wizard-info text-center">結果期</div>';
									break;
							}
							echo '</div>';
							$count += 1;
						}
						echo '<div class="col-md-3 bs-wizard-step active">';
						echo '<div class="text-center bs-wizard-stepnum">Step ' . $count . '</div>';
						echo '<div class="progress">';
						echo '<div class="progress-bar"></div>';
						echo '</div>';
						echo '<a href="#" class="bs-wizard-dot"></a>';
						switch ($count) {
							case 1:
								echo '<div class="bs-wizard-info text-center">幼苗期</div>';
								break;
							case 2:
								echo '<div class="bs-wizard-info text-center">成長期</div>';
								break;
							case 3:
								echo '<div class="bs-wizard-info text-center">開花期</div>';
								break;
							case 4:
								echo '<div class="bs-wizard-info text-center">結果期</div>';
								break;
						}

						echo '</div>';
						$count += 1;
						while ($count <= 4) {
							echo '<div class="col-md-3 bs-wizard-step disabled">';
							echo '<div class="text-center bs-wizard-stepnum">Step ' . $count . '</div>';
							echo '<div class="progress">';
							echo '<div class="progress-bar"></div>';
							echo '</div>';
							echo '<a href="#" class="bs-wizard-dot"></a>';
							switch ($count) {
								case 1:
									echo '<div class="bs-wizard-info text-center">幼苗期</div>';
									break;
								case 2:
									echo '<div class="bs-wizard-info text-center">成長期</div>';
									break;
								case 3:
									echo '<div class="bs-wizard-info text-center">開花期</div>';
									break;
								case 4:
									echo '<div class="bs-wizard-info text-center">結果期</div>';
									break;
							}
							$count += 1;
							echo '</div>';
						}
						?>
					</div>
				</div>

				<h3 id='hisdd' style="font-size:25px;margin-top:2%;text-align:center">
					歷史數據
				</h3>
				<div class="row">
					<div class="col-xs-1 center-block">
						<label class="label label-success" style="display:flex; justify-content: center;">(最近100筆資料)</label>
						<?php
						if (isset($nowgh))
							echo "<a href='./historydata.php?gh=" . $nowgh . "' style='float:right'>更多歷史數據</a> ";
						else
							echo "<a href='#' style='float:right'>更多歷史數據</a> ";
						?>
						<br>
						<div id="linedata" style='margin-bottom: 1%;'></div>
					</div>
				</div>

			</div>
		</div>
</body>
<script>
	var config = inichart('linedata');
	var line = new Morris.Line(config);
	var container_;

	function inichart(_element) {
		option = {
			element: _element,
			data: updateData(),
			xkey: 'record_time',
			ykeys: ["data"],
			labels: ['record_time'], //["sensor data", 'day'],
			xLabelAngle: 30,
			// xLabelMargin: 80,
			xLabelMargin: 10,
			pointSize: 0,
			fillOpacity: 0.8,
			hideHover: "auto",
			behaveLikeLine: true,
			resize: true,
			pointFillColors: ["#ffffff"],
			pointStrokeColors: ["black"],
			lineColors: ["#87AAAA"]
		};
		return option;
	}

	function mqttconnect() {
		const clientId = 'mqttjs_' + Math.random().toString(16).substr(2, 8)
		const host = 'wss://ai.shu.edu.tw:8883';
		const options = {
			keepalive: 60,
			clientId: clientId,
			username: 'yang',
			password: '1234',
			protocolId: 'MQTT',
			protocolVersion: 4,
			clean: true,
			reconnectPeriod: 1000,
			connectTimeout: 30 * 1000,
			will: {
				topic: 'WillMsg',
				payload: 'Connection Closed abnormally..!',
				qos: 0,
				retain: false
			},
		}
		//console.log('Connecting mqtt client')
		client = mqtt.connect(host, options)
		if (nowgh != null) {
			histdata('co2');
		}
		client.on('connect', () => {
			//console.log('connected.');
			if (typeof nowgh !== 'undefined') {
				client.subscribe(nowgh + '/sensor', {
					qos: 2
				})
				//	console.log('subscribe greehouse:' + (nowgh + '/sensor'))
			}
			client.on("message", function(topic, payload) {
				//	console.log(payload);
				var arr = payload.toString().split(',');
				$('h4.soil').text(arr[4] + '%');
				$('h4.humid').text(arr[1] + '%');
				$('h4.lux').text(arr[3] + '(lux)');
				$('h4.co2').text(arr[2] + '(ppm)');
				$('h4.temp').text(arr[0] + '°C');
				if (localStorage.getItem('selectsensor') != null) {
					var sensor = localStorage.getItem('selectsensor');
					//		console.log(sensor);
					var dd = 0;
					if (sensor == 'temp')
						dd = arr[0]
					else if (sensor == 'humid')
						dd = arr[1]
					else if (sensor == 'co2')
						dd = arr[2]
					else if (sensor == 'lux')
						dd = arr[3]
					else if (sensor == 'soil')
						dd = arr[4]
					var m = new Date();
					var dateString = m.getUTCFullYear() + "-" + (m.getUTCMonth() + 1) + "-" + m.getUTCDate() + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ": " + m.getUTCSeconds();
					container_.push({
						'record_time': dateString,
						'data': dd
					})
					line.setData(updateData(container_));
				}
				//	console.log('subscribe greehouse:' + (nowgh + '/sensor'))
			});
		});
	}
	function updateData(arr) //jsontype
	{
		return arr;
	}

	function renameKey(obj, oldKey, newKey) {
		obj[newKey] = obj[oldKey];
		delete obj[oldKey];
	}

	function timestamp2now(obj) {
		for (var key in obj) {
			if (obj.hasOwnProperty(key)) {
				var unix_time = obj[key].record_time;
				var a = new Date(unix_time * 1000);
				var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
				var year = a.getUTCFullYear();
				var month = a.getMonth() + 1;
				var date = a.getUTCDate();
				var hour = a.getHours() < 10 ? "0" + a.getHours() : a.getHours();
				var min = a.getMinutes() < 10 ? "0" + a.getMinutes() : a.getMinutes();
				var sec = a.getSeconds() < 10 ? "0" + a.getSeconds() : a.getSeconds();
				var time = year + '-' + month + '-' + date + " " + hour + ':' + min + ':' + sec;
				obj[key]['record_time'] = time; //time;
				//console.log(obj[key]['record_time']);
			}
		}
		return JSON.stringify(obj);
	}
	//var fill = Morris.Area(inichart('fill'));
	// https://blog.reh.tw/archives/662
	//https://blog.csdn.net/mafan121/article/details/50832873

	function histdata(sensor) {
		var sen = '';
		var txt = '';
		switch (sensor) {
			case 'co2':
				sen = 'co2';
				txt = '二氧化碳'
				break;
			case 'temp':
				sen = 'temp';
				txt = '溫度'
				break;
			case 'humid':
				sen = 'humid';
				txt = '濕度'
				break;
			case 'lux':
				sen = 'lux';
				txt = '光照值'
				break;
			case 'soil_humid':
				sen = 'soil';
				txt = '土壤濕度'
				<?php
				if (isset($_GET['soilpage'])) {
					echo 'sen="' . $soil[$_GET['soilpage']] . '";';
				} else {
					echo 'sen="' . $soil[$ii] . '";';
				}
				?>
				break;
			case defualt:
				break;
		}
		document.getElementById('hisdd').innerHTML = "歷史數據(" + txt + ")";
		localStorage.setItem('selectsensor', sen);
		$('#title-container:hidden').show();
		$.ajax({
			type: 'POST',
			url: "getghdata.php",
			dataType: "json",
			data: {
				sensor: sen,
				gh: '<?php print($nowgh); ?>'
			},
			success: function(data) {
				var arr = JSON.parse(data);
				switch (sensor) {
					case 'co2':
						arr.forEach(obj => renameKey(obj, 'co2', 'data')); //obj,origin name ,update name
						break;
					case 'temp':
						arr.forEach(obj => renameKey(obj, 'temperature', 'data'));
						break;
					case 'humid':
						arr.forEach(obj => renameKey(obj, 'humidity', 'data'));
						break;
					case 'lux':
						arr.forEach(obj => renameKey(obj, 'accumulation_lux', 'data'));
						break;
					case 'soil_humid':
						arr.forEach(obj => renameKey(obj, 'soil_humidity', 'data'));
						break;
					case defualt:
						break;
				}
				var dd = timestamp2now(arr);
				var tmp = JSON.parse(dd);
				container_ = updateData(tmp);
				//console.log(container_);
				line.setData(container_);
				$('#title-container').hide(); //nalysis-result
			},
			error: function(jqXHR) {
				alert('發生錯誤：' + jqXHR.status);
			}

		});
	}
</script>
<script src="../js/bootstrap.js"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</html>