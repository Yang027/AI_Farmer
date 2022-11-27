<?php

include('config.php');
session_start();
//Reset OAuth access token
$gClient->revokeToken($_SESSION['token']);

setcookie("id", "", time() - 60 * 60 * 24 * 30, "/", NULL);
//Destroy entire session data.
session_destroy();
//redirect page to index.php
header('location:homepage_logout.php');
?>