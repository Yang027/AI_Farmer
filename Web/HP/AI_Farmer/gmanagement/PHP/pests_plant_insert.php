<!-- <?php session_start(); ?> -->
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM pest"; //搜尋 *(全部欄位) ，從 表staff
$query2 = "SELECT * FROM plant";

//mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
$query_run2 = mysqli_query($con, $query2); //$con <<此變數來自引入的 db_cn.php
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title>pests_plant_insert.php</title>
</head>

<body>
   <?php
   // 是否是表單送回
   if (isset($_POST["Insert"])) {
      // 開啟MySQL的資料庫連接
      $link = mysqli_connect($db_host, $db_username, $db_password, $database)
         or die("無法開啟MySQL資料庫連接!<br/>");
      mysqli_select_db($link, $database);  // 選擇資料庫
      // 建立新增記錄的SQL指令字串
      $sql = "INSERT INTO pests_plant (pest, plant_id) VALUES ('";
      $sql .= $_POST["pest"] . "','" . $_POST["plant_id"] . "')";
      echo "<b>SQL指令: $sql</b><br/>";
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
         // echo "資料庫新增記錄成功, 影響記錄數: " .
         //   mysqli_affected_rows($link) . "<br/>";
         echo "<script type='text/javascript'>alert('資料庫新增記錄成功! 影響記錄數: " .
            mysqli_affected_rows($link) . "');</script>";
      else
         // die("資料庫新增記錄失敗<br/>");
         echo "<script type='text/javascript'>alert('資料庫新增記錄失敗!');</script>";
      mysqli_close($link);      // 關閉資料庫連接
   }
   ?>

   <form action="pests_plant_insert.php" method="post">
      </br>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">害蟲名稱</label>
         <div class="col-sm-6">
            <select class="form-select" aria-label="Default select example" name="pest">
               <option selected>害蟲名稱</option>
               <?php

               if (mysqli_num_rows($query_run) > 0) {
                  $i = 0;
                  while ($i < mysqli_num_rows($query_run)) {
                     $i += 1;
                     $row = mysqli_fetch_assoc($query_run);
               ?>
                     <option value="<?php echo $row['pest']; ?>"><?php echo $row['pest']; ?></option>
               <?php
                  }
               }
               mysqli_close($con);
               ?>
            </select>
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">植物名稱</label>
         <div class="col-sm-6">
            <select class="form-select" aria-label="Default select example" name="plant_id">
               <option selected>植物名稱</option>
               <?php

               if (mysqli_num_rows($query_run2) > 0) {
                  $i = 0;
                  while ($i < mysqli_num_rows($query_run2)) {
                     $i += 1;
                     $row = mysqli_fetch_assoc($query_run2);
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
      <div class="d-md-flex justify-content-md-center">
         <button type="submit" class="btn" name="Insert" style="background-color: #FEDFE1;">新增</button>
      </div>

      <!-- </br>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPest" class="col-sm-1 col-form-label text-center">害蟲名稱</label>
      <div class="col-sm-6">
        <input type="text" name="pest" class="form-control" id="inputPest">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPlantId" class="col-sm-1 col-form-label text-center">植物名稱</label>
      <div class="col-sm-6">
        <input type="text" name="plant_id" class="form-control" id="inputPlantId">
      </div>
    </div>
    <div class="d-md-flex justify-content-md-center">
      <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Insert" style="background-color: #FEDFE1;">新增</button>
    </div> -->
   </form>

   <!-- <form action="disease_insert.php" method="post">
<tr><td>病害名稱:</td>
   <td><input type="text" name="disease" size ="10"/></td>
</tr><tr><td>簡 介:</td>
   <td><input type="text" name="introduction" size="300"/></td>
</tr><tr><td>特 徵:</td>
   <td><input type="text" name="feature" size="300"/></td>
</tr><tr><td>出現時期:</td>
   <td><input type="text" name="period" size="25"/></td>
</tr><tr><td>預防時期:</td>
   <td><input type="text" name="preventive_period" size="25"/>
</tr><tr><td>驅逐時期:</td>
   <td><input type="text" name="eviction_period" size="25"/>
	 </td></tr>
<hr/>
<input type="submit" name="Insert" value="新增"/>
</form> -->

</body>

</html>