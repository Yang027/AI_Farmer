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
    <!-- <link rel="stylesheet" type="text/css" href="../css/pest_search.css"> -->
    <title>蟲害查詢</title>
    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
    <style>
        body {
            /* background-color: #FDFBF6; */
            background-color: #FFFDF9;
        }

        .table>:not(caption)>*>* {
            padding: 0rem 0rem;
        }

        .cendiv {
            display: flex;
            justify-content: center;
        }

        .seardiv {
            border: 1px solid #8C9F83;
            width: 70%;
            height: 80px;
            /* border-radius:10px; */
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

        .line1 {
            background-color: #C6CEC4;
            border-radius: 100px;
            padding: 15px 20px;
        }

        .my-5 {
            margin-top: 1rem !important;
            margin-bottom: 0rem !important;
        }

        .iimmgg {
            max-width: 100%;
            /*不使用width:100% 是因避免圖片解析度不好，隨父元素被放大時會糊掉*/
            height: auto;
        }

        /* .dd{
            padding: 0px 200px 0px 200px;
        } */
    </style>

</head>

<body>
    <?php
    $url = "";
    // 是否是表單送回
    if (isset($_POST["pest"], $_POST["feature"])) {
        // $dissearch=$_POST["dissearch"];
        // 開啟MySQL的資料庫連接
        $link = mysqli_connect($db_host, $db_username, $db_password, $database)
            or die("無法開啟MySQL資料庫連接!<br/>");
        mysqli_select_db($link, $database);  // 選擇資料庫
        // 建立新增記錄的SQL指令字串
        // $sql = "SELECT * FROM disease where disease like '*$dissearch*'";
        $query = "SELECT * FROM pest"; //搜尋 *(全部欄位) ，從 表staff
        $query .= " WHERE pest like '%" . $_POST["pest"] . "%'and address like '%" . $_POST["feature"] . "%'";
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
        $sql = "SELECT * FROM pest WHERE pest like '%" . $_POST["pest"] . "%'and address like '%" . $_POST["feature"] . "%' LIMIT $offset , $num";
        // echo "<b>SQL指令: $sql</b><br/>";
        $result = mysqli_query($link, $sql);
        $a = mysqli_num_rows($result);
        // echo "<b>SQL指令: $a</b><br/>";

        mysqli_close($link);      // 關閉資料庫連接

        if (mysqli_num_rows($query_runn) == 0) {
            $url = "https://www.google.com/search?q=" . $_POST["pest"];
            // echo $url;
        }
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../homepage_logout.php">
                <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                蟲害查詢
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
                </ul>
            </div>
        </div>
    </nav>
    <div class="row line1 justify-content-center my-5">
        <form class="row" method="post" action="pest_search.php">
            <div class="in" align="center">
                <input size="30" type="text" name="pest" placeholder="害蟲名稱..." list="Npest">
                <input type="image" name="search" src="../icon/search.png" alt="Submit" height="50" width="50">
                <!-- <img type="submit" name="search" src="../icon/search.png" height="50" width="50"> -->
                <datalist id="Npest">
                    <?php
                    include('../sqlconnect.php');
                    $con2 = mysqli_connect("$db_host", "$db_username", "$db_password", "$database");

                    $query2 = "SELECT pest FROM pest";
                    //搜尋 *(全部欄位) ，從 表staff

                    //mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 
                    $query_run2 = mysqli_query($con2, $query2);
                    if (mysqli_num_rows($query_run2) > 0) {
                        $i = 0;
                        while ($i < mysqli_num_rows($query_run2)) {
                            $i += 1;
                            $row = mysqli_fetch_assoc($query_run2);
                    ?>
                            <option value="<?php echo $row['pest']; ?>"><?php echo $row['pest']; ?></option>
                    <?php
                        }
                    }
                    mysqli_close($con2);
                    ?>
                </datalist>
                <div class="nav justify-content-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio1" value="%" checked="checked">
                        <label class="form-check-label" for="inlineRadio1">不拘</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio2" value="枝">
                        <label class="form-check-label" for="inlineRadio2">枝</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio2" value="幹">
                        <label class="form-check-label" for="inlineRadio2">幹</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio2" value="葉">
                        <label class="form-check-label" for="inlineRadio2">葉</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio2" value="果實">
                        <label class="form-check-label" for="inlineRadio2">果實</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="feature" id="inlineRadio2" value="花">
                        <label class="form-check-label" for="inlineRadio2">花</label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-3" align="center">
        <div class="col-2" style="background-color: #E5EBE2;"></div>
        <div class="dd col-8" style="background-color: #E5EBE2;">
            <?php
            if (mysqli_num_rows($result) > 0) {
            ?>
                <table class="table">
                    <thead>
                        <tr>
                            <!-- <th scope="col">#</th> -->
                            <th scope="col">圖示</th>
                            <th scope="col">蟲害</th>
                            <!-- <th scope="col">簡介</th>
                                <th scope="col">特徵</th> -->
                            <th scope="col">發病部位</th>
                            <th scope="col">其他</th>
                        </tr>
                    </thead>
                    <?php
                    // output data of each row
                    $s = 0;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $s++;

                        echo '<tr>';
                        // echo '<td width="50">';
                        // echo $s;
                        // echo '</td>';
                        echo '<td width="50">';
                        echo '<img src="' .  $row['image'] . '" class="iimmgg" alt="...">';
                        // echo $i;
                        echo '</td>';
                        echo '<td width="30">';
                        echo $row['pest'];
                        echo '</td>';
                        // echo '<td width="30%">';
                        // echo $row['introduction'];
                        // echo '</td>';
                        // echo '<td width="30%">';
                        // echo $row['feature'];
                        // echo '</td>';
                        echo '<td width="30">';
                        echo $row['address'];
                        echo '</td>';
                        echo '<td width="30">';
                        echo '<a href="pest_info.php?item=' . $row['pest'] . '" class="test-reset"><font color="#7F5F3F">more-info</font></a>';

                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo ' </table>';
                    // echo ' <h5 style="text-align:center; color:#98814C;>';
                    // echo  '<a href="pest_search.php?page=1">首頁 </a>';
                    // echo '<a href="pest_search.php?page=' . ($pagee - 1) . 'name=' . $_POST["find"] . '"><font color="#98814C">上一頁</a>';
                    // echo ' 當前是第 ' . $pagee . ' 頁 ';
                    // echo '<a href="pest_search.php?page=' . ($pagee + 1) . 'name=' . $_POST["find"] . '"><font color="#98814C">下一頁  </a>';
                    // echo '  共 ' . $totall . ' 頁 ';
                    // echo '<a href="pest_search.php?page=' . $totall . '"><font color="#98814C">尾頁</a>';
                    // echo '</h5>';
                } elseif ($url != "") {
                    ?>
                    <div style="text-align:center;">
                        </br>
                        <h3>暫無資料</h3>
                        <a href="<?php echo $url ?>" target="_blank">點我擊Google了解更多</a>
                    </div>
                <?php
                    $url = "";
                } else {
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <!-- <th scope="col">#</th> -->
                                <th scope="col">圖示</th>
                                <th scope="col">蟲害</th>
                                <!-- <th scope="col">簡介</th>
                                <th scope="col">特徵</th> -->
                                <th scope="col">發病部位</th>
                                <th scope="col">其他</th>
                            </tr>
                        </thead>
                    <?php
                    $query = "SELECT * FROM pest"; //搜尋 *(全部欄位) ，從 表staff
                    $query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php

                    $data = mysqli_num_rows($query_run); //得到總的使用者數
                    // echo "<b>SQL指令: $data</b><br/>";
                    if (isset($_GET['page'])) {
                        $page = (int) $_GET['page'];
                    } else {
                        $page = 1;
                    }
                    //每頁顯示數
                    $num = 10;
                    //得到總頁數
                    $total = ceil($data / $num);
                    if ($page <= 1) {
                        $page = 1;
                    }
                    if ($page >= $total) {
                        $page = $total;
                    }
                    $offset = ($page - 1) * $num;
                    $sqll = "SELECT * FROM pest LIMIT $offset , $num";
                    $resultt = mysqli_query($con, $sqll);
                    // $a = mysqli_num_rows($resultt);

                    $allitem = array(array());
                    if (mysqli_num_rows($query_run) > 0) {
                        $i = 0;
                        $j = 1;
                        $p = 0;
                    }
                    echo '<tbody>';
                    $_SESSION['indexnumber'] = $_GET['num'];
                    while ($i < mysqli_num_rows($resultt)) {
                        $i += 1;
                        $row = mysqli_fetch_assoc($resultt);
                        $item = array($row['pest'], $row['introduction'], $row['feature'], $row['address'], $row['period'], $row['preventive_period'], $row['eviction_period'], $row['predict_policy'], $row['solution']);
                        $allitem[$j] = $item;
                        $j++;
                        echo '<tr onclick="info(this)">';
                        echo '<td width="50">';
                        echo '<img src="' .  $row['image'] . '" class="iimmgg" alt="...">';
                        // echo $i;
                        echo '</td>';
                        echo '<td width="30">';
                        echo $row['pest'];
                        echo '</td>';
                        // echo '<td width="30%">';
                        // echo $row['introduction'];
                        // echo '</td>';
                        // echo '<td width="30%">';
                        // echo $row['feature'];
                        // echo '</td>';
                        echo '<td width="30">';
                        echo $row['address'];
                        echo '</td>';
                        echo '<td width="30">';
                        echo '<a href="pest_info.php?item=' . $row['pest'] . '" class="test-reset"><font color="#7F5F3F">more-info</font></a>';

                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo ' </table>';
                }
                    ?>
        </div>
        <div class="col-2" style="background-color: #E5EBE2;"></div>
        <?php
        if (isset($_POST["pest"], $_POST["feature"])) {
            echo ' <h5 style="text-align:center; color:#98814C;>';
            echo '<a href="pest_search.php">首頁 </a>';
            echo '<a href="pest_search.php?page=' . ($pagee - 1) . 'name=' . $_POST["pest"] . '"><font color="#98814C">上一頁</a>';
            echo ' 當前是第 ' . $pagee . ' 頁 ';
            echo '<a href="pest_search.php?page=' . ($pagee + 1) . 'name=' . $_POST["find"] . '"><font color="#98814C">下一頁  </a>';
            echo '  共 ' . $totall . ' 頁 ';
            // echo '<a href="pest_search.php?page=' . $totall . '"><font color="#98814C">尾頁</a>';
            echo '</h5>';
        } else {

            echo '<h5 style="text-align:center; color:#98814C;>';
            echo '<a href="pest_search.php?page=' . $page . '">首頁 </a>';
            echo '<a href="pest_search.php?page=' . ($page - 1) . '"><font color="#98814C">上一頁</a>';
            echo ' 當前是第 ' . $page . ' 頁 ';
            echo '<a href="pest_search.php?page=' . ($page + 1) . '"><font color="#98814C">下一頁  </a>';
            echo '    共 ' . $total . ' 頁 ';
            // echo '<a href="pest_search.php?page=' . $total . '"><font color="#98814C">尾頁</a>';
            echo '</h5>';
        }

        ?>
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