<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>pest_insert.php</title>
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
    $sql = "INSERT INTO pest (pest, image, introduction, ";
    $sql .= "feature, address, period, preventive_period, eviction_period, predict, medicine) VALUES ('";
    $sql .= $_POST["pest"] . "','" . $_POST["image"] . "','";
    $sql .= $_POST["introduction"] . "','" . $_POST["feature"] . "','";
    $sql .= $_POST["address"] . "','" . $_POST["period"] . "','";
    $sql .= $_POST["preventive_period"] . "','" . $_POST["eviction_period"] . "','";
    $sql .= $_POST["predict"] . "','" . $_POST["medicine"] . "')";
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
      echo "<script type='text/javascript'>alert('資料庫更新記錄失敗!');</script>";
    mysqli_close($link);      // 關閉資料庫連接
  }
  ?>

  <form action="pest_insert.php" method="post">
    </br>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPest" class="col-sm-1 col-form-label text-center">害蟲名稱</label>
      <div class="col-sm-6">
        <input type="text" name="pest" class="form-control" placeholder="ex.潛葉蠅" id="inputPest">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">害蟲簡介</label>
      <div class="col-sm-6">
        <input type="text" name="introduction" class="form-control" placeholder="ex.好發於草花和蔬菜，被啃食的部位會留下有如圖畫般的白色紋路。" id="inputIntroduction">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPassword3" class="col-sm-1 col-form-label text-center">害蟲特性</label>
      <div class="col-sm-6">
        <input type="text" name="feature" class="form-control" placeholder="ex.直接觀察植物外觀時，不容易看到蟲的蹤影，但是在陽光底下，藏在葉裡的幼蟲和蛹則清晣可見。" id="inputPassword3">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputAddress" class="col-sm-1 col-form-label text-center">容易發病部位</label>
      <div class="col-sm-6">
        <input type="text" name="address" class="form-control" placeholder="ex.莖、葉、花、蕾、果實" id="inputAddress">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPeriod" class="col-sm-1 col-form-label text-center">出現時期</label>
      <div class="col-sm-6">
        <input type="text" name="period" class="form-control" placeholder="ex.6月初—10月中" id="inputPeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPreventivePeriod" class="col-sm-1 col-form-label text-center">預防時期</label>
      <div class="col-sm-6">
        <input type="text" name="preventive_period" class="form-control" placeholder="ex.5月中—9月底" id="inputPreventivePeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputEvictionPeriod" class="col-sm-1 col-form-label text-center">驅逐時期</label>
      <div class="col-sm-6">
        <input type="text" name="eviction_period" class="form-control" placeholder="ex.6月初—10月中" id="inputEvictionPeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPredict" class="col-sm-1 col-form-label text-center">防治對策</label>
      <div class="col-sm-6">
        <input type="text" name="predict" class="form-control" placeholder="ex.如果發現到啃食的痕跡和暗褐色的糞便，請檢視周圍環境，一旦發現有尚末潛入果實內部的幼蟲時，便可立即撲滅。若果實有洞，表示幼蟲可能潛入，必須切開果實，才能消滅幼蟲。" id="inputPredict">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputMedicine" class="col-sm-1 col-form-label text-center">藥品對策</label>
      <div class="col-sm-6">
        <input type="text" name="medicine" class="form-control" placeholder="ex.最好在幼蟲開始出沒的6月，選擇適合該種植物的殺蟲劑，仔細噴灑，連嫩葉和花也不要遺漏。蔬菜類、康乃馨、菊花等，如果遭受番茄夜蛾啃食，可使用蘇力菌等藥劑。" id="inputMedicine">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="formFile" class="form-label col-sm-1 col-form-label text-center">圖片</label>
      <div class="col-sm-6">
        <input class="form-control" name="image" type="file" id="formFile">
      </div>
    </div>
    <!-- <div class="row mb-3 justify-content-md-center">
      <label for="inputPassword3" class="col-sm-1 col-form-label text-center">圖片</label>
      <div class="col-sm-6">
        <input type="text" name="image" class="form-control" id="inputPassword3">
      </div>
    </div> -->
    <div class="d-md-flex justify-content-md-center">
      <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Insert" style="background-color: #FEDFE1;">新增</button>
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