<?php
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$database = "aifarmer"; //因為是本地測試，改這一段就好(輸入你指定的資料庫名稱，我指定test_2021，如下圖)

// $db_host = "localhost:8889";
// $db_username = "aifarmer";
// $db_password = "1234";
// $database = "aifarmer"; //因為是本地測試，改這一段就好(輸入你指定的資料庫名稱，我指定test_2021，如下圖)

$con = mysqli_connect("$db_host", "$db_username", "$db_password", "$database");

if($con)
{
    mysqli_query($con,"SET NAMES utf8");
    // echo "連線正確";
}
else
{
    echo '無法連線資料庫 :<br/>' . mysqli_connect_error();
}
?>