<!-- <?php session_start(); ?> -->
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM disease"; //搜尋 *(全部欄位) ，從 表staff

//mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
?>

<!DOCTYPE html>
<html>
<form action="disease_update.php" method="post">
  </br>
  <div class="row mb-3 justify-content-md-center">
    <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">病害名稱</label>
    <div class="col-sm-6">
      <div class="input-group">
        <?php
        echo '<form method="POST" action="">';
        echo '<select  class="form-select" aria-label="Default select example" name="dd" onchange="this.form.submit()">';
        echo '<option disabled selected>病害名稱</option>';
        if (isset($_POST["dd"])) {
          $tmp = $_POST["dd"];
          //echo '<script>alert("' . $tmp . '");</script>';
        }
        if (mysqli_num_rows($query_run) > 0) {
          $i = 0;
          while ($i < mysqli_num_rows($query_run)) {
            $i += 1;
            $row = mysqli_fetch_assoc($query_run);
            if (strcmp($_POST["dd"], $row['disease']) == 0) {
              echo ' <option value=' . $row['disease'] . " selected>" . $row['disease'] . '</option>';
            } else {
              echo ' <option value=' . $row['disease'] . " >" . $row['disease'] . '</option>';
            }
          }
        }
        mysqli_close($con);


        echo '</select>';
        echo '</form>';
        ?>
      </div>
    </div>
  </div>
  
  <form action="disease_update.php" method="post">
    <?php
    if (isset($_POST["dd"])) {
      $tmp = $_POST["dd"];

      include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
      $con = mysqli_connect($db_host, $db_username, $db_password, $database);

      $query2 = "SELECT * FROM disease WHERE disease='" . $_POST["dd"] . "'";

      //mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

      $query_run2 = mysqli_query($con, $query2); //$con <<此變數來自引入的 db_cn.php
   
      if ($query_run2) {
        // echo mysqli_num_rows($query_run);
        if (mysqli_num_rows($query_run2) > 0) {
            while ($row = mysqli_fetch_assoc($query_run2)) {
                // 每跑一次迴圈就抓一筆值，最後放進data陣列中           
                $intro = $row['introduction'];
                $feature = $row['feature'];
                $address = $row['address'];
                $period = $row['period'];
                $preventive = $row['preventive_period'];
                $eviction = $row['eviction_period'];
                $predict = $row['predict_policy'];
                $solution = $row['solution'];
            }
        }
        mysqli_free_result($query_run2);
    }
   ?>
   
      <div class="row mb-3 justify-content-md-center">
        <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">病害簡介</label>
        <div class="col-sm-6">
          <input type="text" name="introduction" class="form-control" id="inputIntroduction" value="<?php echo $intro; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputFeature" class="col-sm-1 col-form-label text-center">病害特性</label>
        <div class="col-sm-6">
          <input type="text" name="feature" class="form-control" id="inputFeature" value="<?php echo $feature; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputAddress" class="col-sm-1 col-form-label text-center">容易發病部位</label>
        <div class="col-sm-6">
          <input type="text" name="address" class="form-control" id="inputAddrss" value="<?php echo $address; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputPeriod" class="col-sm-1 col-form-label text-center">發病時期</label>
        <div class="col-sm-6">
          <input type="text" name="period" class="form-control" id="inputPeriod" value="<?php echo $period; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputPreventivePeriod" class="col-sm-1 col-form-label text-center">預防時期</label>
        <div class="col-sm-6">
          <input type="text" name="preventive_period" class="form-control" id="inputPreventivePeriod" value="<?php echo $preventive; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputEvictionPeriod" class="col-sm-1 col-form-label text-center">驅逐時期</label>
        <div class="col-sm-6">
          <input type="text" name="eviction_period" class="form-control" id="inputEvictionPeriod" value="<?php echo $eviction; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputPredictPolicy" class="col-sm-1 col-form-label text-center">防治對策</label>
        <div class="col-sm-6">
          <input type="text" name="predict_policy" class="form-control" id="inputPredictPolicy" value="<?php echo $predict; ?>">
        </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
        <label for="inputSolution" class="col-sm-1 col-form-label text-center">藥品對策</label>
        <div class="col-sm-6">
          <input type="text" name="solution" class="form-control" id="inputSolution" value="<?php echo $solution; ?>">
        </div>
      </div>
      <!-- <div class="row mb-3 justify-content-md-center">
        <label for="formFile" class="form-label col-sm-1 col-form-label text-center">圖片</label>
        <div class="col-sm-6">
          <input class="form-control" name="img" type="file" id="formFile">
        </div>
      </div> -->
      <input type="text" style="display:none" name="ddd" value="<?php echo $tmp ?>" />
      <div class="d-md-flex justify-content-md-center">
        <button type="submit" class="btn" name="Update" style="background-color: #FEDFE1;">修改</button>
      </div>
    <?php
    } else {
    }
    ?>
  </form>

  <head>
    <meta charset="utf-8" />
    <title>disease_update.php</title>
  </head>

  <body>
    <?php
    // 是否是表單送回
    if (isset($_POST["Update"])) {
      // 開啟MySQL的資料庫連接
      $link = mysqli_connect($db_host, $db_username, $db_password, $database)
        or die("無法開啟MySQL資料庫連接!<br/>");
      mysqli_select_db($link, $database);  // 選擇資料庫
      // 建立更新記錄的SQL指令字串
      $sql = "UPDATE disease SET ";
      $sql .= "introduction='" . $_POST["introduction"] . "',";
      $sql .= "feature='" . $_POST["feature"] . "',";
      $sql .= "address='" . $_POST["address"] . "',";
      $sql .= "period='" . $_POST["period"] . "',";
      $sql .= "preventive_period='" . $_POST["preventive_period"] . "',";
      $sql .= "eviction_period='" . $_POST["eviction_period"] . "',";
      $sql .= "predict_policy='" . $_POST["predict_policy"] . "',";
      $sql .= "solution='" . $_POST["solution"] . "'";
      // $sql .= "solution='" . $_POST["solution"] . "',";
      // $sql .= "img='" . $_POST["img"] . "'";
      $sql .= " WHERE disease = '" . $_POST["ddd"] . "'";
      // echo "<b>SQL指令: $sql</b><br/>"; //print SQL指令
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
      {
        // echo "資料庫更新記錄成功, 影響記錄數: " .
        //    mysqli_affected_rows($link) . "<br/>";
        echo "<script type='text/javascript'>alert('資料庫更新記錄成功! 影響記錄數: " .
          mysqli_affected_rows($link) . "');</script>";
      } else
        //  die("資料庫更新記錄失敗<br/>");
        echo "<script type='text/javascript'>alert('資料庫更新記錄失敗!');</script>";
      mysqli_close($link);      // 關閉資料庫連接
    }
    ?>



    <!-- <form action="Ch12_4_2Update.php" method="post">
病害名稱: <input type="text" name="disease" size ="6"/>
<table border="1">
<tr><td>簡介:</td>
   <td><input type="text" name="introduction" size="25"/>
	 </td></tr>
</table><hr/>
<input type="submit" name="Update" value="更新"/>
</form> -->
  </body>

</html>