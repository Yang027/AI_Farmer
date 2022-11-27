<?php
require_once "control.php";
require_once "config.php";

if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
} else {
    header('Location: username.php?');
    exit();
}
if(isset($token["error"]) != "invalid_grant"){
//     // get data from google
// $oAuth = new Google_Service_Oauth2($gClient);
// $userData = $oAuth->userinfo_v2_me->get();

// echo "<pre>";
// var_dump($userData);
// echo "<pre>";

// if(isset($token["error"]) && ($token["error"] != "invalid_grant")){
     // get data from google
$oAuth = new Google_Service_Oauth2($gClient);
$userData = $oAuth->userinfo_v2_me->get();


//     // var_dump($userData);
    //insert data in the database
    $Controller = new Controller;
   echo $Controller -> insertData(
        array(
            'email' => $userData['email'],
            'id' => $userData['id']
        )
    );
}else{
    header('Location: username.php');
    exit();
}
