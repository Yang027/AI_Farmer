<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>disease_insert.php</title>
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
    $sql = "INSERT INTO disease (disease, introduction, feature, ";
    $sql .= "address,period, preventive_period, eviction_period,predict_policy,solution,image,resource) VALUES ('";
    $sql .= $_POST["disease"] . "','" . $_POST["introduction"] . "','";
    $sql .= $_POST["feature"] . "','" . $_POST["address"] . "','";
    $sql .= $_POST["period"] . "','" . $_POST["preventive_period"] . "','";
    $sql .= $_POST["eviction_period"] . "','" . $_POST["predict_policy"] . "','";
    $sql .= $_POST["solution"] . "','" . $_POST["img"] . "','". $_POST["resource"] . "')";
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

  <form action="disease_insert.php" method="post">
    </br>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputDisease" class="col-sm-1 col-form-label text-center">病害名稱</label>
      <div class="col-sm-6">
        <input type="text" name="disease" class="form-control" placeholder="ex.基腐病" id="inputDisease">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputIntroduction" class="col-sm-1 col-form-label text-center">病害簡介</label>
      <div class="col-sm-6">
        <input type="text" name="introduction" class="form-control" placeholder="ex.發病與鬱金香、小蒼蘭、百合等球根植物的傳染病。起因是球根、莖、塊莖和鱗莖等被土壤中的菌絲侵襲，導致莖葉產生黃化、枯萎的疾病。 " id="inputIntroduction">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputFeature" class="col-sm-1 col-form-label text-center">病害特性</label>
      <div class="col-sm-6">
        <input type="text" name="feature" class="form-control" placeholder="ex.染病的植物葉子一般會從外側逐漸黃化。一旦受到病原菌感染，即使將球根掘起，病情還是會繼續惡化，最後乾枯成木乃伊，連子球也難以倖免。" id="inputFeature">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputAddress" class="col-sm-1 col-form-label text-center">容易發病部位</label>
      <div class="col-sm-6">
        <input type="text" name="address" class="form-control" placeholder="ex.根、球根、莖、葉" id="inputAddrss">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPeriod" class="col-sm-1 col-form-label text-center">發病時期</label>
      <div class="col-sm-6">
        <input type="text" name="period" class="form-control" placeholder="ex.4月初—5月底" id="inputPeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPreventivePeriod" class="col-sm-1 col-form-label text-center">預防時期</label>
      <div class="col-sm-6">
        <input type="text" name="preventive_period" class="form-control" placeholder="ex.（種植球根之前）9月中—11月底" id="inputPreventivePeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputEvictionPeriod" class="col-sm-1 col-form-label text-center">驅逐時期</label>
      <div class="col-sm-6">
        <input type="text" name="eviction_period" class="form-control" placeholder="ex.4月初—5月底" id="inputEvictionPeriod">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputPredictPolicy" class="col-sm-1 col-form-label text-center">防治對策</label>
      <div class="col-sm-6">
        <input type="text" name="predict_policy" class="form-control" placeholder="ex.購買球根時，記得避開表面已經長出褐色斑點、長出黴菌、有部分腐爛的個體。發現植株發病時，要連同球根將周圍的土壤一併挖起來丟棄。因為病原菌會殘留於土壤中，如果... " id="inputPredictPolicy">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputSolution" class="col-sm-1 col-form-label text-center">藥品對策</label>
      <div class="col-sm-6">
        <input type="text" name="solution" class="form-control" placeholder="ex.如果是栽培鬱金香或洋蔥苗，可以先把球根浸泡在免賴得殺菌劑再種植。" id="inputSolution">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="formFile" class="form-label col-sm-1 col-form-label text-center">圖片</label>
      <div class="col-sm-6">
        <input class="form-control" name="img" type="file" id="formFile">
      </div>
    </div>
    <div class="row mb-3 justify-content-md-center">
      <label for="inputResource" class="col-sm-1 col-form-label text-center">圖片來源</label>
      <div class="col-sm-6">
        <input type="text" name="resource" class="form-control" placeholder="ex.https://www....." id="inputResource">
      </div>
    </div>
    <div class="d-md-flex justify-content-md-center">
      <button type="submit" class="btn" name="Insert" style="background-color: #FEDFE1;">新增</button>
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