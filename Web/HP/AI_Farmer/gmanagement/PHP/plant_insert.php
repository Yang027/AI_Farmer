<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title>plant_insert.php</title>
</head>

<body>
   <?php
   // 是否是表單送回
   if (isset($_POST["Insert"])) {
      // 開啟MySQL的資料庫連接
      include('upload.php');
      $file = "../images/" . $_FILES["file"]["name"];
      move_uploaded_file($tmp_name,"upload/".$name); 
      $link = mysqli_connect($db_host, $db_username, $db_password, $database)
         or die("無法開啟MySQL資料庫連接!<br/>");
      mysqli_select_db($link, $database);  // 選擇資料庫
      // 建立新增記錄的SQL指令字串

      $sql = "INSERT INTO plant (plant_id, introduction, feature, ";
      $sql .= "variety, seeding, feild_planting, harvest, precautions, command_isseu, image, classification,link) VALUES ('";
      $sql .= $_POST["plant_id"] . "','" . $_POST["introduction"] . "','";
      $sql .= $_POST["feature"] . "','" . $_POST["variety"] . "','";
      $sql .= $_POST["seeding"] . "','" . $_POST["feild_planting"] . "','";
      $sql .= $_POST["harvest"] . "','" . $_POST["precautions"] . "','";
      $sql .= $_POST["command_isseu"] . "','" . $file . "','";
      $sql .= $_POST["classification"] . "','" . $_POST["link"] . "')";
      // echo "<b>SQL指令: $sql</b><br/>";
      //送出UTF8編碼的MySQL指令
      mysqli_query($link, 'SET NAMES utf8');
      if (mysqli_query($link, $sql)) // 執行SQL指令
         // echo "資料庫新增記錄成功, 影響記錄數: " .
         //    mysqli_affected_rows($link) . "<br/>";
         echo "<script type='text/javascript'>alert('資料庫新增記錄成功! 影響記錄數: " .
            mysqli_affected_rows($link) . "');</script>";
      else
         // die("資料庫新增記錄失敗<br/>");
         echo "<script type='text/javascript'>alert('資料庫新增記錄失敗!');</script>";
      mysqli_close($link);      // 關閉資料庫連接
   }
   ?>
   

   <form action="plant_insert.php" method="post" enctype="multipart/form-data" action="upload.php">
      </br>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputPlantId" class="col-sm-1 col-form-label text-center">植物名稱</label>
         <div class="col-sm-6">
            <input type="text" name="plant_id" class="form-control" placeholder="ex.冬瓜" id="inputPlantId">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">簡 介</label>
         <div class="col-sm-6">
            <input type="text" name="introduction" class="form-control" placeholder="ex.不僅夏天能採收，還可從冬天儲存到早春，所以被稱為冬瓜。清淡的口味是各種料理的最佳材料。除可供蔬菜食用外，尚可製成冬瓜糖和冬瓜茶。" id="inputIntroduction">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputFeature" class="col-sm-1 col-form-label text-center">栽培特性</label>
         <div class="col-sm-6">
            <input type="text" name="feature" class="form-control" placeholder="ex.高溫性蔬菜，生長的最適溫度為25～30°C，於瓜類作物中生育期需時較長。" id="inputFeature">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputVariety" class="col-sm-1 col-form-label text-center">品種</label>
         <div class="col-sm-6">
            <input type="text" name="variety" class="form-control" placeholder="ex.有早生、晚生等不同的品種，一般以小型冬瓜品種較適合庭園栽培。常被栽種的品種如可供作毛瓜及小冬瓜用的「東和」、「小惠」、「清心」、「珍福」等，都屬於適合一般小家庭食用的品種。" id="inputVariety">
         </div>
      </div>

      <div class="row mb-3 justify-content-md-center">
         <label for="inputSeeding" class="col-sm-1 col-form-label text-center">撒種</label>
         <div class="col-sm-6">
            <input type="text" name="seeding" class="form-control" placeholder="ex. 北部地區:2月上旬—3月底以及7月上旬—9月上旬     中、南部地區:8月中—隔年2月下旬" id="inputSeeding">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputFeildPlanting" class="col-sm-1 col-form-label text-center">定植</label>
         <div class="col-sm-6">
            <input type="text" name="feild_planting" class="form-control" placeholder="ex. 北部地區:3月底—6月初以及8月下旬—10月底     中、南部地區:10月初—隔年3月下旬" id="inputFeildPlanting">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputHarvest" class="col-sm-1 col-form-label text-center">收穫</label>
         <div class="col-sm-6">
            <input type="text" name="harvest" class="form-control" placeholder="ex. 北部地區:5月下旬—7月底以及10月中—12月中     中、南部地區:11月中—隔年8月下旬" id="inputHarvest">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputPrecautions" class="col-sm-1 col-form-label text-center">注意事項</label>
         <div class="col-sm-6">
            <input type="text" name="precautions" class="form-control" placeholder="ex.冬瓜的蔓非常容易生長，因此，常有發生莖葉變得十分脆弱、著果不良的情形。為防止這種現象，將栽培畦間擴大為約200公分，每株之間約90公分左右，並於親蔓的4~5節處進行摘心作業。" id="inputPrecautions">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="inputCommandIsseu" class="col-sm-1 col-form-label text-center">常見問題</label>
         <div class="col-sm-6">
            <input type="text" name="command_isseu" class="form-control" placeholder="ex.Q.定植之後的生長情況不好  A.早點整理田地，事先準備好畦並澆水，進行鋪設塑膠布處理定植之後的生長變惡劣，主要的原因應是定植時根部受到嚴重傷害，或是地溫太低......" id="inputCommandIsseus">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
         <label for="formFile" class="form-label col-sm-1 col-form-label text-center">圖片</label>
         <div class="col-sm-6">
            <input class="form-control" name="file" type="file" id="file">
         </div>
      </div>
      <div class="row mb-3 justify-content-md-center">
      <label for="inputLink" class="col-sm-1 col-form-label text-center">圖片來源</label>
      <div class="col-sm-6">
        <input type="text" name="link" class="form-control" placeholder="ex.https://www....." id="inputLink">
      </div>
    </div>
      <div class="row mb-3 justify-content-md-center">
         <div class="col-sm-6">
            <select class="form-select col-sm-1 col-form-label text-center" aria-label="Default select example" name="classification">
               <option selected>未選擇分類</option>
               <option value="水果">花卉</option>
               <option value="蔬菜">蔬菜</option>
               <option value="水果">水果</option>
               <option value="其他">其他</option>
            </select>
         </div>
         <div class="col-sm-1">
            <div class="d-md-flex justify-content-md-center">
               <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Insert" style="background-color: #FEDFE1;">新增</button>
            </div>
         </div>
      </div>
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
