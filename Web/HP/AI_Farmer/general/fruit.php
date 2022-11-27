<?php
require_once '../config.php';
require_once "../control.php";
?>

<?php session_start(); ?>
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_DEPRECATED);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <title>水果知識庫</title>

  <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">

  <style>
    body {
      /* background-color: #FDFBF6; */
      background-color: #FFFDF9;
    }

    #a {
      color: rgb(229, 146, 23);
    }

    #more {
      display: none;
    }

    /* .card-img-top {
      height: 180px
    }

    .card-body {
     height: 250px;
    } */

    /* .container {
      padding-left: pl-2;
      padding-right: pl-2;
    } */
    .btn-outline-primary {
      color: #98814C;
      border-color: #98814C;
    }

    .btn-check:active+.btn-outline-primary,
    .btn-check:checked+.btn-outline-primary,
    .btn-outline-primary.active,
    .btn-outline-primary.dropdown-toggle.show,
    .btn-outline-primary:active,
    .btn-outline-primary:hover {
      color: #f6f9fe;
      background-color: #98814C;
      border-color: #98814C;
    }

    .btn-check:focus+.btn-outline-primary,
    .btn-outline-primary:focus {
      -webkit-box-shadow: 0 0 0 .25rem rgba(194, 141, 50, 0.5);
      box-shadow: 0 0 0 .25rem rgba(194, 141, 50, 0.5)
    }

    .btn-check:active+.btn-outline-primary:focus,
    .btn-check:checked+.btn-outline-primary:focus,
    .btn-outline-primary.active:focus,
    .btn-outline-primary.dropdown-toggle.show:focus,
    .btn-outline-primary:active:focus {
      -webkit-box-shadow: 0 0 0 .25rem rgba(194, 141, 50, 0.5);
      box-shadow: 0 0 0 .25rem rgba(194, 141, 50, 0.5)
    }

    .btn-outline-primary.disabled,
    .btn-outline-primary:disabled {
      color: #98814C;
    }

    input[type=text] {
      padding: 6px 20px;
      box-shadow: #795e42;
      border: 1px #795e42 solid;
      border-radius: 4px;
    }

    input[type=text]:focus {
      box-shadow: #795e42;
      border: 1px #555 solid;
    }

    .button1 {
      background-color: #F6F2EC;
      border-radius: 8px;
      border: 2px solid #735630;
      float: right;
    }

    .in {
      display: inline;
    }

    .line {
      border-style: double;
      border-width: 8px;
      border-color: #B1CAAB;
      border-radius: 50px;
    }

    .line1 {
      background-color: #C6CEC4;
      border-radius: 100px;
      padding: 15px 20px;
    }

    .my-5 {
      margin-top: 1rem !important;
      margin-bottom: 2rem !important;
    }
  </style>
</head>

<body>
  <?php
  // 是否是表單送回
  if (isset($_POST["find"])) {
    // 開啟MySQL的資料庫連接
    $link = mysqli_connect($db_host, $db_username, $db_password, $database)
      or die("無法開啟MySQL資料庫連接!<br/>");
    mysqli_select_db($link, $database);  // 選擇資料庫
    // 建立新增記錄的SQL指令字串
    $query = "SELECT * FROM plant"; //搜尋 *(全部欄位) ，從 表staff
    $query .= " WHERE classification='水果' and plant_id like '%" . $_POST["find"] . "%'";
    // echo "<b>SQL指令: $query</b><br/>";
    //送出UTF8編碼的MySQL指令
    $query_runn = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
    $dataa = mysqli_num_rows($query_runn); //得到總的使用者數
    // echo "<b>SQL指令: $dataa</b><br/>";
    // $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

    if (isset($_GET['pagee'])) {
      $pagee = (int) $_GET['pagee'];
    } else {
      $pagee = 1;
    }
    //每頁顯示數
    $num = 9;
    //得到總頁數
    $totall = ceil($dataa / $num);
    if ($pagee <= 1) {
      $pagee = 1;
    }
    if ($pagee >= $totall) {
      $pagee = $totall;
    }
    $offset = ($pagee - 1) * $num;
    $sql = "SELECT * FROM plant WHERE plant_id like '%" . $_POST["find"] . "%' LIMIT $offset , $num";
    echo "<b>SQL指令: $sql</b><br/>";
    $result = mysqli_query($link, $sql);
    $a = mysqli_num_rows($result);
    // echo "<b>SQL指令: $a</b><br/>";
    mysqli_close($link);      // 關閉資料庫連接

    if (isset($_POST["find"]) && mysqli_num_rows($result) == 0) {
      $url = "https://www.google.com/search?q=" . $_POST["find"];
      // echo $url;
    }
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
    <div class="container-fluid">
      <a class="navbar-brand" href="../homepage_logout.php" style="color: #763c16;">
        <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
        水果資料
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../homepage_logout.php" style="color: #1D3124;">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="all.php">知識庫</a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              知識庫
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="fruit.php">水果</a></li>
              <li><a class="dropdown-item" href="veg.php">蔬菜</a></li>
              <li><a class="dropdown-item" href="flower.php">花卉</a></li>
              <li><a class="dropdown-item" href="other.php">其他</a></li>
            </ul>
          </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              病蟲害知識庫
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="pest_search.php">蟲害查詢</a></li>
              <li><a class="dropdown-item" href="disease_search.php">病害查詢</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="suggest.php">聯絡我們</a>
            <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              聯絡我們
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="contact.php">尋求協助</a></li>
              <li><a class="dropdown-item" href="suggest.php">給予建議</a></li>
            </ul>
          </li> -->
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="window.location = '<?php echo $login_url; ?>'">溫室環控</a>
          </li>
          <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
        </ul>
        <!-- <button type="button" class="btn btn-outline-secondary" location.href="login.html">登入</button> -->
        <input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = '<?php echo $login_url; ?>'">
      </div>
    </div>
  </nav>
  <div class="row line1 justify-content-center my-5">
    <form class="row" method="post" action="fruit.php">
      <div class="in" align="center">
        <input size="30" type="text" name="find" placeholder="Search...">
        <input type="image" name="search" src="../icon/search.png" alt="Submit" height="50" width="50">
        <!-- <img type="submit" name="search" src="../icon/search.png" height="50" width="50"> -->
      </div>
    </form>
    <div class="filter-btns shadow-md rounded-pill text-center col-auto">
      <a class="filter-btn btn rounded-pill btn-outline-primary border-0 m-md-2 px-md-4" data-filter=".project" href="all.php">全部</a>
      <a class="filter-btn btn rounded-pill btn-outline-primary border-0 m-md-2 px-md-4 active" data-filter=".business" href="fruit.php">水果</a>
      <a class="filter-btn btn rounded-pill btn-outline-primary border-0 m-md-2 px-md-4" data-filter=".marketing" href="veg.php">蔬菜</a>
      <a class="filter-btn btn rounded-pill btn-outline-primary border-0 m-md-2 px-md-4" data-filter=".social" href="flower.php">花卉</a>
      <a class="filter-btn btn rounded-pill btn-outline-primary border-0 m-md-2 px-md-4" data-filter=".graphic" href="other.php">其他</a>
    </div>
  </div>

  <?php
  if (mysqli_num_rows($result) > 0) {
    $i = 0;
    echo '<div class="line">';
    while ($i < mysqli_num_rows($result)) {
      $j = 0;
      echo '<div class="card-group">';
      echo '<div class="container">';
      echo '<div class="row">';
      while ($j < 3) {
        $row = mysqli_fetch_assoc($result);
        $j += 1;
        $i += 1;
        if ($i <= mysqli_num_rows($result)) {
          echo '<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">';
          echo '<div class="p-3">';
          echo '<div class="card">';
          echo '<img src="' . $row['image'] . '" height="250" class="card-img-top" alt="...">';
          echo '<div class="card-body">';
          echo ' <h5 class="card-title">';
          echo $row['plant_id'];
          echo '</h5>';
          echo '<p class="card-text" id="a">簡介:"' . $row['introduction'] . '"</p>';
          echo '<a href="fruit_info.php?item=' . $row['plant_id'] . '"><button class="button1">Read more <img class="vertical-align:middle;" src="../icon/click.png" height="25" width="25"></button></a>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      }
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    echo '</div>';
    echo ' <h5 style="text-align:center; color:#98814C;>';
    echo  '<a href="fruit.php?page=1">首頁 </a>';
    echo '<a href="fruit.php?page=' . ($pagee - 1) . 'name=' . $_POST["find"] . '"><font color="#98814C">上一頁</a>';
    echo ' 當前是第 ' . $pagee . ' 頁 ';
    echo '<a href="fruit.php?page=' . ($pagee + 1) . 'name=' . $_POST["find"] . '"><font color="#98814C">下一頁  </a>';
    echo '  共 ' . $totall . ' 頁 ';
    echo '<a href="fruit.php?page=' . $totall . '"><font color="#98814C">尾頁</a>';
    echo '</h5>';
  } elseif ($url != "") {
    echo ' <div style="text-align:center;">';
    echo '</br>';
    echo '<h3>暫無資料</h3>';
    echo '<a href="' . $url . '" target="_blank">點擊至Google了解更多</a>';
    echo ' </div>';
  } else {
    $query = "SELECT * FROM plant WHERE classification='水果'"; //搜尋 *(全部欄位) ，從 表staff

    //mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 
    $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
    $data = mysqli_num_rows($query_run);
    if (isset($_GET['page'])) {
      $page = (int) $_GET['page'];
    } else {
      $page = 1;
    }
    //每頁顯示數
    $num = 9;
    //得到總頁數
    $total = ceil($data / $num);
    if ($page <= 1) {
      $page = 1;
    }
    if ($page >= $total) {
      $page = $total;
    }
    $offset = ($page - 1) * $num;
    $sqll = "SELECT * FROM plant WHERE classification='水果' LIMIT $offset , $num";
    $resultt = mysqli_query($con, $sqll);
    $a = mysqli_num_rows($resultt);
    // echo "<b>SQL指令: $a</b><br/>";

    echo '<div class="line">';
    $i = 0;
    while ($i < mysqli_num_rows($resultt)) {
      $j = 0;
      echo '<div class="card-group">';
      echo '<div class="container">';
      echo '<div class="row">';
      while ($j < 3) {
        $row = mysqli_fetch_assoc($resultt);
        $j += 1;
        $i += 1;
        if ($i <= mysqli_num_rows($resultt)) {
          echo '<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">';
          echo '<div class="p-3">';
          echo '<div class="card">';
          echo '<img src="' . $row['image'] . '" height="250" class="card-img-top" alt="...">';
          echo '<div class="card-body">';
          echo ' <h5 class="card-title">';
          echo $row['plant_id'];
          echo '</h5>';
          echo '<p class="card-text" id="a">簡介:"' . $row['introduction'] . '"</p>';
          echo '<a href="fruit_info.php?item=' . $row['plant_id'] . '"><button class="button1">Read more <img class="vertical-align:middle;" src="../icon/click.png" height="25" width="25"></button></a>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      }
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    echo '</div>';
    echo ' <h5 style="text-align:center; color:#98814C;>';
    echo  '<a href="fruit.php?page=1">首頁 </a>';
    echo '<a href="fruit.php?page=' . ($page - 1) . '"><font color="#98814C">上一頁</a>';
    echo ' 當前是第 ' . $page . ' 頁 ';
    echo '<a href="fruit.php?page=' . ($page + 1) . '"><font color="#98814C">下一頁  </a>';
    echo '  共 ' . $total . ' 頁 ';
    echo '<a href="fruit.php?page=' . $total . '"><font color="#98814C">尾頁</a>';
    echo '</h5>';
  }
  $con->close();
  ?>

  </div>
  </div>

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