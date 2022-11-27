<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
session_start();

$email = $_SESSION['email'];
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
<script>
	var client;
	var selected = false;

	// function show() {
	// 	var x = document.querySelectorAll("[id='selector']");
	// 	for (var i = 0; i < x.length; i++) {
	// 		if (x[i].style.display === "none") {
	// 			x[i].style.display = "block";
	// 		} else {
	// 			x[i].style.display = "none";

	// 		}
	// 	}
	// }
</script>
<?php
//$selected = false;
if (isset($_GET["currgh"])) {
	$nowgh = $_GET["currgh"]; //讀option value
	echo "<script>var nowgh='" . $nowgh . "';</script>";
	echo "<script>var selected = true;</script>";
} else {
	echo "<script>var selected = false;</script>";
}


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
	<title>溫室環控</title>
	<!-- Google Font Roboto -->

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



</head>


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
				<!-- <input type="button" id='login' class="btn btn-link" style='outline:none' value="<?php //echo $name; 
																										?>" onclick="location.href='login.html'"> -->
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

		<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 13%; ">
			<a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">

				<span class="fs-4"><?php echo isset($name) ? "HI, " . $name : "請先登錄"; ?></span>
			</a>
			<hr>
			<br>

			<ul class="nav nav-pills flex-column mb-auto">
				<li style="font-size:18px">
					<a href="greenhouse_main.php" class="nav-link text-white" style="text-align:  center;">
						<!-- <a href="greenhouse_main.php" class="nav-link text-white" aria-current="page" style="text-align:  center;"> -->
						<img src="../images/home-16.png" width="16" height="16" style="float:left;margin-top: 3px;"></img>
						<span>我的溫室</span>
					</a>

				</li>

				<li class="nav-item" style="font-size:18px">
					<a href="greenhouse_control.php" class="nav-link active" style="text-align:  center;">
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

			<!-- <h1 class="ml3" style="text-align:right">Welcome to AI Farmer!</h1>
			<br> -->
			<!-- <img src="../images/green.png" alt="" width="5%" height='5%'> -->
			<?php
			if (isset($name)) {
				include('../sqlconnect.php');

				$temp = 0;
				$humid = 0;
				$co2 = 0;
				$lux = 0;
				$soil = 0;
				// $greenhouse = array();
				// sql語法存在變數中
				$sql = 'SELECT `greenhouse_ID` FROM `usergreenhouse` INNER JOIN `google_users` ON
				 `usergreenhouse`.mail=`google_users`.email WHERE `username`="' . $name . '"'; //{abner_yang}要換成現在使用者的username
				// 用mysqli_query方法執行(sql語法)將結果存在變數中
				//var_dump($sql);
				$result = mysqli_query($con, $sql);
				$flag = false;
				echo '<form method="GET" action="">';
				echo '<h3 style="font-size:20px;margin-top:20px">溫室選擇';
				echo '<select name="currgh" style="float:right" onchange="this.form.submit()">';
				echo '<option value="" disabled selected>  --請選擇你欲查看的溫室--  </option>';

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
				} else {
					echo "<option value=''>" . '您目前無溫室' . "</option>";
				}
				echo '</select>';
				echo '</span>';
				echo '</form>';
				if ($flag) {

					echo '<script>selected=true;</script>';
					$sql = 'SELECT fantime,hottime,bumptime,luxtime FROM `greenhouse_customT`   WHERE  `greenhouse_ID`="' . $nowgh . '"';
					$result = mysqli_query($con, $sql);
					if ($result) {
						if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);
							// 每跑一次迴圈就抓一筆值，最後放進data陣列中			

							$tfan = $row['fantime'];
							$thot = $row['hottime'];
							$tbump = $row['bumptime'];
							$tlux = $row['luxtime'];
							// echo changetime($tfan);
						}
						mysqli_free_result($result);
					}
					$sql = 'SELECT temperature,humidity,co2,need_lux_value,soil_humidity FROM `greenhouse_customv`   WHERE  `greenhouse_ID`="' . $nowgh . '"';
					$result = mysqli_query($con, $sql);
					if ($result) {
						if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);

							// 每跑一次迴圈就抓一筆值，最後放進data陣列中
							$temp = $row['temperature'];
							$humid = $row['humidity'];
							$co2 = $row['co2'];
							$lux = $row['need_lux_value'];
							$soil = $row['soil_humidity'];
						}
						mysqli_free_result($result);
					}
				}
			}
			?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>

			<!-- <div class="icon">
				
				<h4>手動控制溫室數據</h4></img>
			</div>
			<span id='warn' style='text-align: center; display:block; font-size: 30px;'></span> -->

			<form method="post" id=selector>
				<!-- action="control.php">-->
				<h2 style="color:#304D63">數據調整
					<button class="button-9" role="button" name='btnsubmit' style="cursor:pointer">
						<h3>確認</h3>
					</button>
				</h2>
				<!-- <div class="selector" id="selector" style="margin-top: 1.5%;"> -->
				<div class="selector">
					<i class="fa-solid fa-droplet fa-3x" style="color:#51A8DD;"><br>
						<b style=" display: flex;justify-content: center; font-size:20px;color:#51A8DD;">濕度</b>
					</i>
					<span style=" display: flex;justify-content: center;  ">
						<div id="appearance1" name='humid' style="color:FB9AA; " data-value=0></div>
					</span>
				</div>
				<div class="selector">
					<i class="fa-solid fa-sun fa-3x" style="color: #f37915;">
						<b style=" display: flex;justify-content: center; font-size:20px;color: #f37915;">日照值</b>
					</i>
					<span style=" display: flex;justify-content: center;  ">
						<div id="appearance2" name='lux' style="color:FB9AA; " data-value=0></div>
					</span>
				</div>
				<div class="selector">
					<i class="fa-solid fa-temperature-three-quarters fa-3x" style="color:#e15042;">
						<b style=" display: flex;justify-content: center; font-size:20px;color:#e15042">溫度</b>
					</i>
					<span style=" display: flex;justify-content: center;  ">
						<div id="appearance3" name='temp' style="color:FB9AA;margin-left:10px;font-size:150px" data-value=0></div>
					</span>
				</div>
				<div class="selector">
					<i class="fa-solid  fa-seedling fa-3x" style="color:#876633;">
						<b style=" display: flex;justify-content: center; font-size:20px;color:#876633;">土壤濕度</b>
					</i>
					<span style=" display: flex;justify-content: center;  ">
						<div id="appearance4" name='soil' style="color:FB9AA;margin-left:10px;font-size:large" data-value=0></div>
					</span>
				</div>
				<div class="selector">
					<i class="fa-solid fa-smog fa-3x" style="color:#91989F;">
						<b style=" display: flex;justify-content: center; font-size:20px;color:#91989F;">&nbsp;CO2</b>
					</i>
					<span style=" display: flex;justify-content: center;  ">
						<div id="appearance5" name='co2' style="color:FB9AA;margin-left:10px" data-value=0 value=123></div>
					</span>
				</div>

				<?php
				function changetime($time)
				{
					$time = intval($time);
					$hour = floor($time / 60) > 10 ? strval(floor($time / 60)) : "0" . strval($time / 60);
					$minute = $time - ($hour * 60) > 10 ? strval($time - ($hour * 60)) : "0" . strval($time - ($hour * 60));
					return strval($hour) . ":" . strval($minute);
				}

				?>
				<div class="selector1" id='button-switch'>
					<i class="fa-solid fa-gear fa-3x" style="color:#8FB9AA;">
						<h5>&nbsp;開關</h5>
					</i>
					<div class="check">
						<div class='row'>
							<div class="col">
								<label class="switch">
									<input class="switch-input" type="checkbox" id='hinput' value='3' onchange="swapStyleSheet()">
									<span class="switch-label" data-on="On" data-off="Off"></span>
									<span class="toggle-switch-text" style='text-align: center; display:block;'> <i class="fa-solid fa-droplet fa-1x" style="color:#8FB9AA;"></i>除濕</span>
									<span class="switch-handle"></span>
								</label>
							</div>
							<div class="col">
								<label for="birthdaytime">排程時間</label>
								<input type="time" id="birthdaytime2" name="htime" value=<?php echo isset($thot) ? changetime($thot) : changetime(0); ?>>
							</div>
						</div>


						<br>
						<div class='row' style='display: flex;'>
							<div class='col'>
								<label class="switch">
									<input class="switch-input" type="checkbox" id='binput' value='2' onchange="swapStyleSheet()">
									<span class="switch-label" data-on="On" data-off="Off"></span>
									<span class="toggle-switch-text" style='text-align: center; display:block;'> <i class="fa-solid fa-droplet fa-1x" style="color:#8FB9AA;"></i> 澆水</span>
									<span class="switch-handle"></span>
								</label>
							</div>
							<div class='col'>
								<label for="birthdaytime">排程時間</label>
								<input type="time" id="birthdaytime2" name="wtime" value="<?php echo isset($tbump) ? changetime($tbump) : changetime(0); ?>">
							</div>
						</div>

						<br>

						<div class='row' style='display: flex;'>
							<div class='col'>
								<label class="switch">
									<input class="switch-input" type="checkbox" id='linput' value='1' onchange="swapStyleSheet()">
									<span class="switch-label" data-on="On" data-off="Off"></span>
									<span class="toggle-switch-text" style='text-align: center; display:block;'><i class="fa-solid fa-lightbulb fa-1x" style="color:#8FB9AA;"></i> 開燈</span>
									<span class="switch-handle"></span>

								</label>
							</div>
							<div class='col'>
								<label for="birthdaytime">排程時間</label>
								<input type="time" id="birthdaytime3" name="ltime" value="<?php echo isset($tlux) ? changetime($tlux) : changetime(0); ?>">
							</div>
						</div>

						<br>

						<div class='row'>
							<div class='col'>
								<label class="switch">
									<input class="switch-input" type="checkbox" id='finput' value='0' onchange="swapStyleSheet()">
									<label class="switch-label" data-on="On" data-off="Off"></label>
									<span class="toggle-switch-text" style='text-align: center; display:block;'><i class="fa-solid fa-fan fa-1x" style="color:#8FB9AA;"></i> 風扇</span>
									<span class="switch-handle"></span>
								</label>
							</div>
							<div class='col'>
								<label for="birthdaytime">排程時間</label>
								<input type="time" id="birthdaytime4" name="ftime" value="<?php echo isset($tfan) ? changetime($tfan) : changetime(0); ?>">
							</div>
						</div>
					</div>

				</div>
			</form>
		</div>


		<br />

		<br />

		<!-- <script src="../js/bootstrap.bundle.min.js"></script> -->
		<script>
			$(function() {
				$('form').on('submit', function(e) {
					e.preventDefault();
					$.ajax({
						type: 'post',
						url: 'control.php',
						//dataType: "text",
						data: $('form').serialize(),

						success: function(data) {
							console.log(data);
							client.publish(nowgh.concat('', '/update'), "1");
							alert('更新成功');
						}
					});

				});
			});
			var payload = [0, 0, 0, 0]

			function swapStyleSheet(sheet) { //找到拿個按鈕開
				//判斷方法：checkedValue就是enable，可以用一個array：e.g int[]payload={1,1,0} do sth, do sth, do sth
				//value 1=fan ,value 2=light, value 3=bump
				var checkedValue = null;
				var inputElements = document.getElementsByClassName('switch-input');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						//checkedValue = inputElements[i].value;
						//alert(checkedValue);
						payload[i] = 1
						//break;
					} else payload[i] = 0;
				}
				var tmp = payload.toString().replace(/,/g, '');
				var action = tmp.substring(0, tmp.length);
				console.log(action);
				//alert(nowgh);
				if (typeof nowgh == 'undefined') {

					//client.subscribe(nowgh + '/control')
				}
				//   $nowgh = $_GET["currgh"]; //讀option value
				client.publish(nowgh.concat('', '/control'), action,{qos:2,retain:false});
			}


			function mqttconnect() {
				var x = document.querySelectorAll("[id='selector']");

				if (!selected) {
					var y = document.querySelectorAll("[id='button-switch']");
					y[0].style.display = "none";
					document.getElementById('warn').innerHTML = '請選擇溫室！';
					for (var i = 0; i < x.length; i++)
						x[i].style.display = "none";

				}

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

				console.log('Connecting mqtt client')
				client = mqtt.connect(host, options)

				client.on('connect', () => {
					console.log('connected.');
//					if (typeof nowgh !== 'undefined') {
//						client.subscribe(nowgh + '/control')
//					}
//					client.on("message", function(topic, payload) {
//						console.log(payload);
//						console.log([topic, payload].join(": "));
//						// client.end()
//					});
				});
			}

			$("#appearance1").roundSlider({
				radius: 100,
				width: 22,
				handleSize: "+10",
				sliderType: "min-range",
				value: 0,
				min: 1,
				max: 100,

				change: function(args) {
					console.log(args.value);
					var dataContainer = document.getElementById('appearance1');
					dataContainer.setAttribute('data-value', args.value);
				}
			});
			$("#appearance2").roundSlider({
				radius: 100,
				width: 22,
				handleSize: "+10",
				sliderType: "min-range",
				value: 0,
				min: 0,
				max: 100000,
				change: function(args) {
					console.log(args.value);
					var dataContainer = document.getElementById('appearance2');
					dataContainer.setAttribute('data-value', args.value);
				}
			});
			$("#appearance3").roundSlider({
				radius: 100,
				width: 22,
				handleSize: "+10",
				sliderType: "min-range",
				value: 0,
				min: 1,
				max: 100,
				change: function(args) {
					console.log(args.value);
					var dataContainer = document.getElementById('appearance3');
					dataContainer.setAttribute('data-value', args.value);
				}
			});
			$("#appearance4").roundSlider({
				radius: 100,
				width: 22,
				handleSize: "+10",
				sliderType: "min-range",
				value: 0,
				min: 1,
				max: 1000,
				change: function(args) {
					console.log(args.value);
					var dataContainer = document.getElementById('appearance4');
					dataContainer.setAttribute('data-value', args.value);
				}
			});
			$("#appearance5").roundSlider({
				radius: 100,
				width: 22,
				handleSize: "+10",
				sliderType: "min-range",
				value: 0,
				min: 0,
				max: 10000,
				change: function(args) {
					console.log(args.value);
					var dataContainer = document.getElementById('appearance5');
					dataContainer.setAttribute('data-value', args.value);
				}
			});

			$("#appearance1").roundSlider("setValue", <?php echo  $humid; ?>);
			$("#appearance2").roundSlider("setValue", <?php echo  $lux; ?>);
			$("#appearance3").roundSlider("setValue", <?php echo  $temp; ?>);
			$("#appearance4").roundSlider("setValue", <?php echo  $soil; ?>);
			$("#appearance5").roundSlider("setValue", <?php echo  $co2; ?>);

			//var textWrapper = document.querySelector('.ml3');
			//textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

			//anime.timeline({
			//		loop: true
			//	})
			//	.add({
			//		targets: '.ml3 .letter',
			//		opacity: [0, 1],
			//		easing: "easeInOutQuad",
			//		duration: 2250,
			//		delay: (el, i) => 150 * (i + 1)
			//	}).add({
			//		targets: '.ml3',
			//		opacity: 0,
			//		duration: 1000,
			//		easing: "easeOutExpo",
			//		delay: 1000
			//	});
		</script>
</body>
<!-- JavaScript Bundle with Popper -->
<!-- Option 1: Bootstrap Bundle with Popper -->
<!--<script src="../js/bootstrap.bundle.min.js"></script>-->
<!--<script src="../js/bootstrap.bunble.js"></script>-->
<!--<script src="../js/bootstrap.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</html>
