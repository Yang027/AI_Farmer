<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>溫室歷史數據</title>
    <link rel="stylesheet" type="text/css" href="../css/circle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://kit.fontawesome.com/0e12d05167.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
</head>


<body>
    <h4><a href="./greenhouse_main.php?currgh=<?php echo $_GET['gh'];  ?>">回到溫室</a> </h4>
    <?php
    $sen = 'humid';
    if (isset($_GET['hisselect'])) //hisselect
    {
        $sen = $_GET['hisselect'];
    }
    echo '<h1 style="text-align: center; display:block;" id="tit" ></h1>';
    include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
    $conn = mysqli_connect("192.192.156.30", $db_username, $db_password, $database);
    $gh =  $_GET["gh"];
    $valued = array("濕度", "二氧化碳濃度(ppm)", "溫度(℃)", "光照值(lux)", "土壤濕度");
    $valueo = array("humid", "co2", "temp", "lux", "soil");
    echo '<form method="GET" action="">';
    echo '<input name="gh" value="' . $gh . '" type="hidden"></input>';
    echo '<select name="hisselect" style="float:right" onchange="this.form.submit()">';
    echo '<option value="" disabled selected> --請選擇你欲查看的歷史數據-- </option>';
    $i = 0; //以$i為$nameList這個陣列的索引值
    while ($i < count($valued)) { //count()：陣列長度

        if ($valueo[$i] == $sen) {
            echo "<option value=" . $valueo[$i] . " selected>" . $valued[$i]  . "</option>";
            echo '<script>document.getElementById("tit").innerHTML =  "歷史數據總覽-' . $valued[$i] . '";</script>';
        } else
            echo "<option value=" . $valueo[$i]  . ">" . $valued[$i]  . "</option>";
        $i++;
    }
    echo '</select>';
    echo '</form>';
    $sql = 'SELECT `start_time` FROM `greenhouse_plant`				 
						WHERE `greenhouse_id`="' . $gh . '" and `end_time` =0';
    $result = mysqli_query($conn, $sql);
    $starttime = mysqli_num_rows($result) > 0 ? implode(mysqli_fetch_assoc($result)) : null;
    $colname = "";
    if ($starttime != null) {
        switch ($sen) {
            case 'humid':
                $sql = 'SELECT `humidity`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC  ';
                $colname = 'humidity';
                break;
            case 'co2':
                $colname = 'co2';
                $sql = 'SELECT `co2`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC';
                break;
            case 'temp':
                $colname = 'temperature';
                $sql = 'SELECT `temperature`,record_time
            FROM  `air_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC ';
                break;
            case 'lux':
                $colname = 'accumulation_lux';
                $sql = 'SELECT `accumulation_lux`,record_time
            FROM  `lux_sensor_data`   WHERE `greenhouse_id`="' . $gh . '" and start_time=' . $starttime . ' Order BY record_time DESC ';
                break;
            default:
                $colname = 'soil_humidity';
                $sql = 'SELECT `soil_humidity`,record_time
                FROM  `soil_sensor_data`   WHERE `sensor_id`="' .  $sen . '" and start_time=' . $starttime . ' Order BY record_time DESC ';
                break;
        }
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data_nums = mysqli_num_rows($result); //統計總比數
            $per = 15; //每頁顯示項目數量
            $pages = ceil($data_nums / $per); //取得不小於值的下一個整數
            if (!isset($_GET["page"])) { //假如$_GET["page"]未設置
                $page = 1; //則在此設定起始頁數
            } else {
                $page = intval($_GET["page"]); //確認頁數只能夠是數值資料
            }
            $start = ($page - 1) * $per; //每一頁開始的資料序號
            $result = mysqli_query($conn, $sql . ' LIMIT ' . $start . ', ' . $per) or die("Error");

            echo ' <table width=80%  style="font-family:"Courier New", Courier, monospace;">';
            echo '  <tr>';
            echo '      <td style="text-align: center;font-size:22px">記錄時間</td> ';
            echo '      <td style="text-align: center; font-size:22px">數值</td>';
            echo ' </tr>';

            //輸出資料內容
            while ($row = mysqli_fetch_array($result)) {
                $time  = date("Y-m-d H:i:s",  $row['record_time']); // gmdate("Y-m-d\TH:i:s\Z",  $row['record_time']);
                $data = $row[$colname];
                echo '<tr>';
                echo '  <td style="text-align: center; font-size:2em">' . $time . '</td>';
                echo ' <td style="text-align: center;font-size:2em">' . $data . '</td>';
                echo ' </tr>';
            }

            echo '</table>';
            echo ' <br />';
            echo '<span style="text-align: center; display:block;">';
            echo '<h4>共 ' . $data_nums . ' 筆-在 ' . $page . ' 頁-共 ' . $pages . ' 頁</h4>';
            echo " <h4><a href=?gh=" . $gh . "&hisselect=" . $sen . "&page=1>首頁</a> ";
            echo "第 ";
            for ($i = 1; $i <= $pages; $i++) {
                if ($page - 3 < $i && $i < $page + 3) {
                    echo "<a href=?gh=" . $gh . "&hisselect=" . $sen . "&page=" . $i . ">" . $i . "</a> ";
                }
            }
            echo " 頁 <a href=?gh=" . $gh . "&hisselect=" . $sen . "&page=" . $pages . ">末頁</a></h4><br /><br />";
            echo '</span>';
            $conn->close();
        }
    } else {

        //沒有歷史資料
    }
    ?>
</body>

</html>