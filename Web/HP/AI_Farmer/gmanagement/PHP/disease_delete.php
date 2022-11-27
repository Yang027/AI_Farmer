<!-- <?php //session_start(); ?> -->
<?php
include('../sqlconnect.php');  //這是引入剛剛寫完，用來連線的.php
$con = mysqli_connect($db_host, $db_username, $db_password, $database);

$query = "SELECT * FROM disease"; //搜尋 *(全部欄位) ，從 表staff

//mysqli_query << PHP 有很多種...指令(?) ，這是其中一個，我現在還都是學到甚麼用什麼，沒辦法自己看手冊，然後實驗+學習使用。 

$query_run = mysqli_query($con, $query); //$con <<此變數來自引入的 db_cn.php

?>


<!DOCTYPE html>
<html>

<!-- 這裡 -->
</br>
<!-- <div class="nav justify-content-start"> -->
<form class="row g-12" action="disease_delete.php" method="post">
   <div class="col-2"></div>
   <div class="col-8">
      <select class="form-select" aria-label="Default select example" name="disease">
         <option selected>要刪除的病害</option>
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
      </select>
   </div>
   <div class="col-2">
      <input type="submit" name="Delete" value="刪除" style="background-color: #FEDFE1;" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" />
   </div>
   <!-- </div> -->

</form>


<head>
   <meta charset="utf-8" />
   <title>disease_delete.php</title>
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
      $sql = "DELETE FROM disease ";
      $sql .= " WHERE disease = '" . $_POST["disease"] . "'";
      echo "<b>SQL指令: $sql</b><br/>";
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
      {
         // echo "資料庫刪除記錄成功, 影響記錄數: " .
         //    mysqli_affected_rows($link) . "<br/>";
         echo "<script type='text/javascript'>alert('資料庫刪除記錄成功! 影響記錄數: " .
            mysqli_affected_rows($link) . "');</script>";
      } else
         // die("資料庫刪除記錄失敗<br/>");
         echo "<script type='text/javascript'>alert('資料庫刪除記錄失敗!');</script>";

      mysqli_close($link);      // 關閉資料庫連接
   ?>
      <!-- <script type="text/javascript">
         $(document).ready(function() {
            //Check if the current URL contains '#'
            if (document.URL.indexOf("#") == -1) {
               // Set the URL to whatever it was plus "#".
               url = document.URL + "#";
               location = "#";

               //Reload the page
               location.reload(true);
            }
         });
      </script> //刪2次才會消失
    -->
      <!-- <script type='text/javascript'>
         (function() {
            if (window.localStorage) {
               if (!localStorage.getItem('firstLoad')) {
                  localStorage['firstLoad'] = true;
                  window.location.reload();
               } else
                  localStorage.removeItem('firstLoad');
            }
         })();
      </script> //刪1次會跳2次通知
      -->
      <!-- <script type='text/javascript'>
         $('#div-id').triggerevent(function() {
            $('#div-id').html(newContent);
         });
      </script> //刪2次才會消失 -->
   <?php

   }

   ?>


   <!-- 這裡 -->

   <!-- <form action="disease_delete.php" method="post">
      <table border="1">
         <tr>
            <td>病害名稱:</td>
            <td><input type="text" name="disease" size="10" /></td>
         </tr>
      </table>
      <hr>
      <input type="submit" name="Delete" value="刪除" />
   </form> -->
</body>

</html>