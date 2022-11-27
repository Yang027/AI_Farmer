<?php
require_once('connectphp.php');

$humid = $_POST['appearance1'];
$lux = $_POST['appearance2'];
$temp = $_POST['appearance3'];
$soil = $_POST['appearance4'];
$co2 = $_POST['appearance5'];
$currgh = $_POST['currgh'];

$sql = 'SELECT `greenhouse_id` FROM `greenhouse_customv`  WHERE `greenhouse_id`="' . $currgh . '"'; //{abner_yang}要換成現在使用者的username
// 用mysqli_query方法執行(sql語法)將結果存在變數中
$result = mysqli_query($conn, $sql);

if ($result) {
	if (mysqli_num_rows($result) > 0) {
		//updat
		$sql =   'UPDATE `greenhouse_customv` SET temperature=' . $temp . ' WHERE greenhouse_id="' . $currgh . '"';
		$result = mysqli_query($conn, $sql);
		$sql =   'UPDATE `greenhouse_customv` SET humidity=' . $humid . ' WHERE greenhouse_id="' . $currgh . '"';
		$result = mysqli_query($conn, $sql);
		$sql =   'UPDATE `greenhouse_customv` SET co2=' . $co2 . ' WHERE greenhouse_id="' . $currgh . '"';
		$result = mysqli_query($conn, $sql);
		$sql =   'UPDATE `greenhouse_customv` SET soil_humidity=' . $soil . ' WHERE greenhouse_id="' . $currgh . '"';
		$result = mysqli_query($conn, $sql);
		$sql =   'UPDATE `greenhouse_customv` SET need_lux_value=' . $lux . ' WHERE greenhouse_id="' . $currgh . '"';
		$result = mysqli_query($conn, $sql);
	} else {
		//insert new one 
		$sql = 'INSERT INTO `greenhouse_customv` (greenhouse_id,temperature,humidity,co2,need_lux_value,soil_humidity) VALUES ("' . $currgh . '","' . $temp . '","' . $humid . '","' . $co2 . '","' . $lux . '","' . $soil . '")';
		$result = mysqli_query($conn, $sql);
	}
}
function caculateTime($str)
{
	list($hour, $minute) = explode(':', $str);
	var_dump($hour);
	return (intval($hour) * 60) + intval($minute) > 0 ? (intval($hour) * 60) + intval($minute) : -1;
	//傳回收到的時間 
}
//-------------------------
//排程
$hottime =  caculateTime($_POST['htime']);
$watertime = caculateTime($_POST['wtime']);
$luxtime = caculateTime($_POST['ltime']);
$fantime = caculateTime($_POST['ftime']);

 

$sql = 'SELECT `greenhouse_id` FROM `greenhouse_customT`  WHERE `greenhouse_id`="' . $currgh . '"'; //{abner_yang}要換成現在使用者的username
// 用mysqli_query方法執行(sql語法)將結果存在變數中
$result = mysqli_query($conn, $sql);

if ($result) {
	if (mysqli_num_rows($result) > 0) {
		//update
		if ($hottime > 0) {
			$sql =   'UPDATE `greenhouse_customT` SET hottime=' . $hottime . ' WHERE greenhouse_id="' . $currgh . '"';
			$result = mysqli_query($conn, $sql);
		}	 
		if ($watertime > 0) {
			$sql =   'UPDATE `greenhouse_customT` SET bumptime=' . $watertime . ' WHERE greenhouse_id="' . $currgh . '"';
			$result = mysqli_query($conn, $sql);
		}
		if ($fantime > 0) {
			$sql =   'UPDATE `greenhouse_customT` SET fantime=' . $fantime . ' WHERE greenhouse_id="' . $currgh . '"';
			$result = mysqli_query($conn, $sql);
		}		
		if ($luxtime  > 0) {
			$sql =   'UPDATE `greenhouse_customT` SET luxtime=' . $luxtime . ' WHERE greenhouse_id="' . $currgh . '"';
			$result = mysqli_query($conn, $sql);
		}
	} else {
		//insert new one 
		$sql = 'INSERT INTO `greenhouse_customT` (greenhouse_id,temptime,humidtime,co2time,soiltime,luxtime)
VALUES ("' . $currgh . '","' . $temptime . '","' . $humidtime . '","' . $co2time . '","' . $soiltime . '","' . $luxtime . '")';

		$result = mysqli_query($conn, $sql);
	}
}
//--------
