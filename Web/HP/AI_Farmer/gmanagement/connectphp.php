<?php
$host = 'localhost';
$dbuser = 'root';
$dbpw = 'root';
//$dbname ='aifarmer';

$link = mysqli_connect($host,$dbuser,$dbpw,"aifarmer");
if($link)
{
    mysqli_query($link,"SET NAMES utf8");
    // echo "連線正確";
}
else
{
    echo '無法連線資料庫 :<br/>' . mysqli_connect_error();
}
?>