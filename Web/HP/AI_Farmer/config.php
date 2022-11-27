<?php
require_once 'google-api/vendor/autoload.php';

// include('google-api/vendor/autoload.php'); 
$gClient = new Google_Client();
$gClient->setClientId("633172609903-shpp4qbdi47udh64m6g2th1q8i68gb24.apps.googleusercontent.com");
$gClient->setClientSecret("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");
$gClient->setApplicationName("AIfarmer Login");
$gClient->setRedirectUri("https://ai.shu.edu.tw/GTopic2022/AI_Farmer/controller.php");
//$gClient->setRedirectUri("http://localhost/AIfarmer/controller.php");

$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

// login URL
$login_url = $gClient->createAuthUrl();
?>





