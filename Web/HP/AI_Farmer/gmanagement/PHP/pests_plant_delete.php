<?php //session_start(); 
?>
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM pest"; //搜尋 *(全部欄位) ，從 表staff
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
   <title>pests_plant_delete.php</title>
</head>

<body>
   <?php
   // 是否是表單送回
   // echo "hi";
   // if (isset($_POST["pp"])) {
   // $ppp=$_POST["pp"];
   if (isset($_POST["Delete"])) {
      // echo "hi-----------------";
      // 開啟MySQL的資料庫連接
      $link = mysqli_connect($db_host, $db_username, $db_password, $database)
         or die("無法開啟MySQL資料庫連接!<br/>");
      mysqli_select_db($link, $database);  // 選擇資料庫
      // 建立刪除記錄的SQL指令字串
      $sql = "DELETE FROM pests_plant ";
      $sql .= " WHERE pest = '" . $_POST["ppp"] . "' AND plant_id='" . $_POST["plant_id"] . "'";
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
   // }
   // else  if (isset($_POST["pp"])) {
   //    echo "hi";
   // }
   ?>

   <form action="pests_plant_delete.php" method="post">
      </br>

      <!-- <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">植物名稱</label>
         <div class="col-sm-6">
            <select class="form-select" aria-label="Default select example" name="plant_id">
               <option selected>植物名稱</option> -->


      <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">害蟲名稱</label>
         <div class="col-sm-6">
            <div class="input-group">
               <?php
               echo '<form method="POST" action="">';
               echo '<select  class="form-select" aria-label="Default select example" name="pp" onchange="this.form.submit()">';
               echo '<option disabled selected>害蟲名稱</option>';
               if (isset($_POST["pp"])) {
                  $tmp = $_POST["pp"];
                  //echo '<script>alert("' . $tmp . '");</script>';
               }
               if (mysqli_num_rows($query_run) > 0) {
                  $i = 0;
                  while ($i < mysqli_num_rows($query_run)) {
                     $i += 1;
                     $row = mysqli_fetch_assoc($query_run);
                     if (strcmp($_POST["pp"], $row['pest']) == 0) {
                        echo ' <option value=' . $row['pest'] . " selected>" . $row['pest'] . '</option>';
                     } else {
                        echo ' <option value=' . $row['pest'] . " >" . $row['pest'] . '</option>';
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

      <form action="pests_plant_delete.php" method="post">
         <?php
         if (isset($_POST["pp"])) {
            $tmp = $_POST["pp"];
            
            include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
            $con = mysqli_connect($db_host, $db_username, $db_password, $database);

            $query2 = "SELECT * FROM pests_plant WHERE pest='" . $_POST["pp"] . "'";

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
            <?php         
            ?>
            <input type="text" style="display:none" name="ppp" value="<?php echo $tmp?>" />
            <div class="d-md-flex justify-content-md-center">
               <button type="submit" class="btn" name="Delete" style="background-color: #FEDFE1;">刪除</button>
            </div>
         <?php
         } else {
         }
         ?>
      </form>
</body>

</html>