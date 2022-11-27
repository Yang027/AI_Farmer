<?php
require_once 'config.php';
require_once "control.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <title>登入</title>
  <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
  <style>
    h1 {
      text-align: center;
    }
  </style>
</head>

<body>

  <h1><img src="images/logo.jpg" alt="" width="36" height="30" class="d-inline-block align-text-center">HI 家人們ヽ(#`Д´)ﾉ</h1>
  <form>
    <label for="exampleInputUserID1" class="form-label" style="font-size:18px">Login with</label>
    <button onclick="window.location = '<?php echo $login_url; ?>'" type="button" class="btn" style="background-color: #F6F4E8;">
      <img src="images/google.png" width="115" height="35">
    </button>
    <!-- <a class="dropdown-item" href="#" onclick="location.href='register.html'">沒有帳號ଘ(੭ˊ꒳​ˋ)੭✧</a> -->

  </form>
  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bunble.js"></script>
  <script src="js/bootstrap.js"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>