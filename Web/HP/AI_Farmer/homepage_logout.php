<?php
session_start();
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
  <title>歡迎來到AIfarmer～</title>
  <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">


</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
    <div class="container-fluid">
      <a class="navbar-brand" href="homepage_logout.php" style="color: #763c16;">
        <img src="images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
        AI Farmer
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#" style="color: #1D3124;">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="general/all.php">知識庫</a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              知識庫
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="general/fruit.php">水果</a></li>
              <li><a class="dropdown-item" href="general/veg.php">蔬菜</a></li>
              <li><a class="dropdown-item" href="general/flower.php">花卉</a></li>
              <li><a class="dropdown-item" href="general/other.php">其他</a></li>
            </ul>
          </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              病蟲害知識庫
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="general/pest_search.php">蟲害查詢</a></li>
              <li><a class="dropdown-item" href="general/disease_search.php">病害查詢</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="general/suggest.php">聯絡我們</a>
            <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              聯絡我們
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="general/contact.php">尋求協助</a></li>
              <li><a class="dropdown-item" href="general/suggest.php">給予建議</a></li>
            </ul>
          </li> -->
          </li>
          <li class="nav-item">
          <?php
            if (isset($_SESSION['email'])) {
              echo '<a class="nav-link" href="#" onclick="window.location =\'http://localhost/AIfarmer/greenhouse_control/greenhouse_main.php\'"; ?>溫室環控</a>';
            } else {
              echo '<a class="nav-link" href="#" onclick="window.location ='.'\'' .  $login_url . '\'" >溫室環控</a>';
            }
            ?>
            <!-- <a class="nav-link" href="#" onclick="window.location = '<?php echo $login_url; ?>'">溫室環控</a> -->
            <!-- <a class="nav-link" href="loginpage.php">溫室環控</a> -->
          </li>
          <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
        </ul>
        <!-- <a class="nav-link" href="gmanagement/manage.html" style="color: #1D3124;">管理</a> -->

        <!-- <button type="button" class="btn btn-outline-secondary" location.href="login.html">登入</button> -->
        <!-- <input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = '//echo $login_url; '"> -->
        <?php
        if (isset($_SESSION['email'])) {
          //$email =  $_SESSION['email'];
          echo '<input type="button" class="btn btn-outline-secondary" value="登出" onclick="location.href=\'' . 'googlelogout.php\'' . '">';
        } else {
          echo '<input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = \'' . $login_url . '\'">';
        }
        ?>
      </div>
    </div>
  </nav>
  <div class="row">
    <div class="col">
      <div class="collapse multi-collapse" id="multiCollapseExample1">
        <div class="card card-body">
          Some placeholder content for the first collapse component of this multi-collapse example. This panel is hidden
          by default but revealed when the user activates the relevant trigger.
        </div>
      </div>
    </div>
    <div class="col">
      <div class="collapse multi-collapse" id="multiCollapseExample2">
        <div class="card card-body">
          Some placeholder content for the second collapse component of this multi-collapse example. This panel is
          hidden by default but revealed when the user activates the relevant trigger.
        </div>
      </div>
    </div>
  </div>
  <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" data-bs-interval="10000">
        <img src="images/greenhouse-tomatoes.jpg" height="650" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5 style="color: #ffffff;">aifarmer makes your life eazier!</h5>
          <p style="color: #f4baba;">payee/cherry/zis/wei/yang</p>
        </div>
      </div>
      <div class="carousel-item" data-bs-interval="2000">
        <img src="images/2.jpg" height="650" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5 style="color: #ffffff;">gaybar二人組</h5>
          <p style="color: #f4baba;">ggayyyyyyyyyy</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/3.jpg" height="650" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5 style="color: #ffffff;">Third slide label</h5>
          <p style="color: #f4baba;">Some representative placeholder content for the third slide.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>





  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bunble.js"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>
