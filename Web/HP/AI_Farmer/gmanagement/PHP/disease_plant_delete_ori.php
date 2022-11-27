<!-- <?php session_start(); ?> -->
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM disease"; //搜尋 *(全部欄位) ，從 表staff
// $query2 = "SELECT * FROM plant";

//mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php
// $query_run2 = mysqli_query($con, $query2); //$con <<此變數來自引入的 db_cn.php
$dis = "病害名稱";
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title>disease_plant_delete.php</title>
</head>

<body>
   <?php
   // 是否是表單送回
   if (isset($_POST["Delete"])) {
      // 開啟MySQL的資料庫連接
      $link = mysqli_connect($db_host, $db_username, $db_password, $database)
         or die("無法開啟MySQL資料庫連接!<br/>");
      mysqli_select_db($link, $database);  // 選擇資料庫
      // 建立刪除記錄的SQL指令字串
      $sql = "DELETE FROM disease_plant ";
      $sql .= " WHERE disease = '" . $_POST["disease"] . "' AND plant_id='" . $_POST["plant_id"] . "'";
      echo "<b>SQL指令: $sql</b><br/>";
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
         // echo "資料庫刪除記錄成功, 影響記錄數: " .
         //    mysqli_affected_rows($link) . "<br/>";
         echo "<script type='text/javascript'>alert('資料庫刪除記錄成功! 影響記錄數: " .
            mysqli_affected_rows($link) . "');</script>";
      else
         // die("資料庫刪除記錄失敗<br/>");
         echo "<script type='text/javascript'>alert('資料庫刪除記錄失敗!');</script>";
      mysqli_close($link);      // 關閉資料庫連接
   }
   ?>
   <form action="disease_plant_delete.php" method="post">
      </br>
      <div class="row mb-3 justify-content-md-center">
      <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">病害名稱</label>
         <div class="col-sm-6">
            <div class="input-group">
            <select class="form-select col-sm-1 col-form-label text-center " id="inputGroupSelect04" aria-label="Example select with button addon" name="disease">
               <option selected>病害名稱</option>
               <?php

               if (mysqli_num_rows($query_run) > 0) {
                  $i = 0;
                  while ($i < mysqli_num_rows($query_run)) {
                     $i += 1;
                     $row = mysqli_fetch_assoc($query_run);
               ?>
                     <option value="<?php echo $row['disease']; ?>"><?php echo $row['disease']; ?></option>

               <?php
                  }
               }
               mysqli_close($con);
               ?>
               <div class="col-sm-1">
                  <div class="d-md-flex justify-content-md-center">
            </select>
            <button type="submit" class="btn btn-outline-secondary" name="Select" style="background-color: #FEDFE1;">確認</button>
            <!-- <button class="btn btn-outline-secondary" type="button">Button</button> -->
                   </div>
                </div>
          </div>
      </div>
      <!-- </form> -->
      <?php
      // echo $dis;
      // $dis = $POST['disease'];
      // echo "gy";
      // echo $dis;
      if (isset($_POST["Select"])) {
         include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
         $con = mysqli_connect($db_host, $db_username, $db_password, $database);

         $query2 = "SELECT * FROM disease_plant WHERE disease='" . $_POST["disease"] . "'";

         //mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

         $query_run2 = mysqli_query($con, $query2); //$con <<此變數來自引入的 db_cn.php
      ?>
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
            <button type="submit" class="btn" name="Delete" style="background-color: #FEDFE1;">刪除</button>
         </div>
      <?php
      } else {
      }
      ?>
      <!-- </br>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputDisease" class="col-sm-1 col-form-label text-center">病害名稱</label>
      <div class="col-sm-6">
        <input type="text" name="disease" class="form-control" id="inputDisease">
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
      <!-- </form> -->
      <!-- <form action="Ch12_4_2Delete.php" method="post">
      <table border="1">
         <tr>
            <td>病害名稱:</td>
            <td><input type="text" name="disease" size="10" /></td>
         </tr>
      </table>
      <hr>
      <input type="submit" name="Delete" value="刪除" />-->
   </form>
</body>

</html>