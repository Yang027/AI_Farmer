<?php
header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] == "POST") { //如果是 POST 請求
    require_once('connectphp.php');
    $sen = $_POST["sensor"];
    $gh =  $_POST["gh"];

    if ($sen != null && $gh != null) {

        $sql = 'SELECT `start_time` FROM `greenhouse_plant`				 
						WHERE `greenhouse_id`="' . $gh . '" and `end_time` =0';
        $result = mysqli_query($conn, $sql);
        $starttime = mysqli_num_rows($result) > 0 ? implode(mysqli_fetch_assoc($result)) : null;
        if ($starttime != null) {
            switch ($sen) {
                case 'humid':
                    $sql = 'SELECT `humidity`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC  LIMIT 100';
                    break;
                case 'co2':
                    $sql = 'SELECT `co2`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC  LIMIT 100';
                    break;
                case 'temp':
                    $sql = 'SELECT `temperature`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC  LIMIT 100';
                    break;
                case 'lux':
                    $sql = 'SELECT `accumulation_lux`,record_time
            FROM  `lux_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC  LIMIT 100';
                    break;
                default:
                    //no 
                    $sql = 'SELECT `soil_humidity`,record_time
                FROM  `soil_sensor_data`   WHERE `sensor_id`="' .  $sen . '" and start_time=' . $starttime . ' Order BY record_time DESC  LIMIT 100';
                    break;
            }
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $output = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(json_encode($output));
                $conn->close();
            } else
                echo json_encode(array('errorMsg' => 'no data in sql'));
        } else
            echo json_encode(array('errorMsg' => 'no data in sql'));
    } else {
        //回傳 errorMsg json 資料
        echo json_encode(array(
            'errorMsg' => '資料未輸入完全！'
        ));
    }
} else {
    //回傳 errorMsg json 資料
    echo json_encode(array(
        'errorMsg' => '請求無效，只允許 POST 方式訪問！'
    ));
}
