<!-- <?php session_start(); ?> -->
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM plant"; //搜尋 *(全部欄位) ，從 表staff

//mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
?>

<!DOCTYPE html>
<html>

<form action="plant_update.php" method="post">
   </br>
   <div class="row mb-3 justify-content-md-center">
      <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">植物名稱</label>
      <div class="col-sm-6">
         <select class="form-select" aria-label="Default select example" name="plant_id">
            <option selected>植物名稱</option>
            <?php

            if (mysqli_num_rows($query_run) > 0) {
               $i = 0;
               while ($i < mysqli_num_rows($query_run)) {
                  $i += 1;
                  $row = mysqli_fetch_assoc($query_run);
            ?>
                  <option value="<?php echo $row['plant_id']; ?>"><?php echo $row['plant_id']; ?></option>
            <?php
               }
            }
            mysqli_close($con);
            ?>
         </select>
      </div>
   </div>
   <!-- <div class="row mb-3 justify-content-md-center">
      <label for="inputPest" class="col-sm-1 col-form-label text-center">病害名稱</label>
      <div class="col-sm-6">
        <input type="text" name="disease" class="form-control" id="inputDisease">
      </div>
    </div> -->
    <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">簡 介</label>
         <div class="col-sm-6">
            <input type="text" name="introduction" class="form-control" id="inputIntroduction">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputFeature" class="col-sm-1 col-form-label text-center">栽培特性</label>
         <div class="col-sm-6">
            <input type="text" name="feature" class="form-control" id="inputFeature">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputVariety" class="col-sm-1 col-form-label text-center">品種</label>
         <div class="col-sm-6">
            <input type="text" name="variety" class="form-control" id="inputVariety">
         </div>
      </div>

      <div class="row mb-3 justify-content-md-center">
         <label for="inputSeeding" class="col-sm-1 col-form-label text-center">撒種</label>
         <div class="col-sm-6">
            <input type="text" name="seeding" class="form-control" id="inputSeeding">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputFeildPlanting" class="col-sm-1 col-form-label text-center">定植</label>
         <div class="col-sm-6">
            <input type="text" name="feild_planting" class="form-control" id="inputFeildPlanting">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputHarvest" class="col-sm-1 col-form-label text-center">收穫</label>
         <div class="col-sm-6">
            <input type="text" name="harvest" class="form-control" id="inputHarvest">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputPrecautions" class="col-sm-1 col-form-label text-center">注意事項</label>
         <div class="col-sm-6">
            <input type="text" name="precautions" class="form-control" id="inputPrecautions">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputCommandIsseu" class="col-sm-1 col-form-label text-center">常見問題</label>
         <div class="col-sm-6">
            <input type="text" name="command_isseu" class="form-control" id="inputCommandIsseus">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="formFile" class="form-label col-sm-1 col-form-label text-center">圖片</label>
         <div class="col-sm-6">
            <input class="form-control" name="image" type="file" id="formFile">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <div class="col-sm-6">
            <select class="form-select col-sm-1 col-form-label text-center" aria-label="Default select example" name="classification">
               <option selected>未選擇分類</option>
               <option value="蔬菜">花卉</option>
               <option value="蔬菜">蔬菜</option>
               <option value="水果">水果</option>
               <option value="其他">其他</option>
            </select>
         </div>
         <div class="col-sm-1">
            <div class="d-md-flex justify-content-md-center">
               <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Update" style="background-color: #FEDFE1;">修改</button>
            </div>
         </div>
      </div>
</form>

<head>
   <meta charset="utf-8" />
   <title>plant_update.php</title>
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
      $sql = "UPDATE plant SET ";
      $sql .= "introduction='" . $_POST["introduction"] . "',";
      $sql .= "feature='" . $_POST["feature"] . "',";
      $sql .= "variety='" . $_POST["variety"] . "',";
      $sql .= "seeding='" . $_POST["seeding"] . "',";
      $sql .= "feild_planting='" . $_POST["feild_planting"] . "',";
      $sql .= "harvest='" . $_POST["harvest"] . "',";
      $sql .= "precautions='" . $_POST["precautions"] . "',";
      $sql .= "command_isseu='" . $_POST["command_isseu"] . "',";
      $sql .= "image='" . $_POST["image"] . "',";
      $sql .= "classification='" . $_POST["classification"] . "'";
      $sql .= " WHERE plant_id = '" . $_POST["plant_id"] . "'";
      echo "<b>SQL指令: $sql</b><br/>";
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
         // echo "資料庫更新記錄成功, 影響記錄數: ". 
         //      mysqli_affected_rows($link) . "<br/>";
         echo "<script type='text/javascript'>alert('資料庫更新記錄成功! 影響記錄數: " .
            mysqli_affected_rows($link) . "');</script>";
      else
         // die("資料庫更新記錄失敗<br/>");
         echo "<script type='text/javascript'>alert('資料庫更新記錄失敗!');</script>";
      mysqli_close($link);      // 關閉資料庫連接
   }
   ?>
   <!-- <form action="Ch12_4_2Update.php" method="post">
      病害名稱: <input type="text" name="disease" size="6" />
      <table border="1">
         <tr>
            <td>簡介:</td>
            <td><input type="text" name="introduction" size="25" />
            </td>
         </tr>
      </table>
      <hr />
      <input type="submit" name="Update" value="更新" />
   </form> -->
</body>

</html>