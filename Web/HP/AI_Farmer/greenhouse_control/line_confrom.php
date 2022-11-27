<!DOCTYPE html>
<html lang="tw">
<?php
echo '完成綁定';
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
?>
<?php
if (isset($_GET['code'])) {
    $data = [
        'grant_type' => "authorization_code",
        'code' => $_GET['code'],
        'redirect_uri' => 'https://ai.shu.edu.tw/GTopic2022/AI_Farmer/greenhouse_control/line_confrom.php',
        'client_id' => 'wjpr67eEMW1Hk6NWDt8RUV',
        'client_secret' => 'k1pLfBs4EUFmJIlsFDmJOQ8dFrDzQBcUUXJ340QZwy0'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-bot.line.me/oauth/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        http_build_query($data)
        // json_encode($data)//"{grant_type:authorization_code,code:".$_GET['code'].",redirect_uri:http://127.0.0.1:8888/AIfarmer/greenhouse_control/line_confrom.php,client_id:wjpr67eEMW1Hk6NWDt8RUV,client_secret:k1pLfBs4EUFmJIlsFDmJOQ8dFrDzQBcUUXJ340QZwy0}"
        // "grant_type=authorization_code&code=" . $val . "&redirect_uri=http://127.0.0.1:8888/AIfarmer/greenhouse_control/line_confrom.php&client_id=wjpr67eEMW1Hk6NWDt8RUV&client_secret=k1pLfBs4EUFmJIlsFDmJOQ8dFrDzQBcUUXJ340QZwy0"
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if (!$err) {
        $_json = json_decode($response, true);
        $_token = $_json['access_token'];
        $mail = $_GET['state'];
        $sql = "INSERT INTO `line_notify` (`email`, `token`) VALUES ('" . $mail . "', '" . $_token . "');";
        $result = mysqli_query($con, $sql);
    }
}
?>

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>

<body>

</body>

</html>