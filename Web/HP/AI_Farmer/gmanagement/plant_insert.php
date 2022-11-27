<?php session_start(); ?>
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_DEPRECATED);

require_once '../config.php';
require_once "../control.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <title>新增植物</title>


</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(248, 195, 195);">
    <div class="container-fluid">
      <a class="navbar-brand" href="../homepage_logout.php">
        <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
        新增 植物 ᕦ( ᐛ )ᕡ

      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../homepage_logout.php" style="color: #1D3124;">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" font-weight="bold;">
              新增
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="pest_insert.php">害蟲</a></li>
              <li><a class="dropdown-item" href="disease_insert.php">病害</a></li>
              <li><a class="dropdown-item" href="plant_insert.php">植物</a></li>
              <li><a class="dropdown-item" href="pests_plant_insert.php">害蟲影響植物</a></li>
              <li><a class="dropdown-item" href="disease_plant_insert.php">病害影響植物</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              刪除
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <!-- <li><a class="dropdown-item" href="pest_delete.php">害蟲</a></li>
              <li><a class="dropdown-item" href="disease_delete.php">病害</a></li>
              <li><a class="dropdown-item" href="plant_delete.php">植物</a></li> -->
              <li><a class="dropdown-item" href="pests_plant_delete.php">害蟲影響植物</a></li>
              <li><a class="dropdown-item" href="disease_plant_delete.php">病害影響植物</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              修改
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="pest_update.php">害蟲</a></li>
              <li><a class="dropdown-item" href="disease_update.php">病害</a></li>
              <li><a class="dropdown-item" href="plant_update.php">植物</a></li>
              <!-- <li><a class="dropdown-item" href="pests_plant_update.php">害蟲影響植物</a></li>
              <li><a class="dropdown-item" href="disease_plant_update.php">病害影響植物</a></li> -->
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="window.location = '<?php echo $login_url; ?>'">溫室</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="adduser.php">新增溫室</a>
          </li>
          <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
        </ul>
      </div>
    </div>
  </nav>
  <!-- <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </nav> -->

  <?php
  include('PHP/plant_insert.php');  //這是引入剛剛寫完，用來連線的.php
  ?>


  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/bootstrap.bunble.js"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>