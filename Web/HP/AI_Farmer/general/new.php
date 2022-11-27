<?php
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
    <title></title>

    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">

</head>

<body onload="ini()">
    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    include dirname(__FILE__) . '/PHPMailer/src/Exception.php';
    include dirname(__FILE__) . '/PHPMailer/src/PHPMailer.php';
    include dirname(__FILE__) . '/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer();
    $mail->SMTPSecure = "ssl";
    $mail->CharSet = "big5"; //設定郵件編碼 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->CharSet = "utf-8";    //信件編碼
    $mail->Username = "aifarmer2022@gmail.com";        //帳號，例:example@gmail.com
    $mail->Password = "mbqnyrjkygdkzdzy";        //密碼
    $mail->IsSMTP();
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    $mail->Encoding = "base64";

    $mail->IsHTML(true);     //內容HTML格式

    $mail->AddAddress("aifarmer2022@gmail.com");   //收件者信箱

    if (isset($_POST["Pest"])) {
        $mail->From = $_POST["mail"]; //寄件者
        $mail->FromName = $_POST["mail"]; //寄件者
        $mail->Subject =  "新增害蟲"; //信件標題


        $msg = "害蟲名稱: " . $_POST["pest"] . "\n害蟲簡介: " . $_POST["pintroduction"] . "\n害蟲特性: " . $_POST["pfeature"];
        $msg .= "\n容易發病部位: " . $_POST["paddress"] . "\n出現時期: " . $_POST["pperiod"] . "\n預防時期: " . $_POST["ppreventive_period"];
        $msg .= "\n驅逐時期: " . $_POST["peviction_period"] . "\n防治對策: " . $_POST["ppredict"] . "\n藥品對策: " . $_POST["pmedicine"];
        $msg .= "\n會影響的植物: " . $_POST["pplant"] . "\n提交者的email: " . $_POST["mail"]; //信件內容

        $mail->Body = $msg; //信件內容
        //  $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->AddAddress("aifarmer2022@gmail.com");   //收件者信箱

        if ($mail->Send()) {
            //    echo "信件已經發送成功。";//寄信成功就會顯示的提示訊息
            echo "<script type='text/javascript'>alert('我們已收到您的回覆，感謝您！');</script>";
        } else {
            echo "<script type='text/javascript'>alert('喔喔 失敗了～ 請再試一次');</script>";
        }
        // echo "信件發送失敗！"; //寄信失敗顯示的錯誤訊息

    } else  if (isset($_POST["Plant"])) {
        $mail->From = $_POST["mail"]; //寄件者
        $mail->FromName = $_POST["mail"]; //寄件者
        $mail->Subject = "新增植物"; //信件標題

        $msg = "植物名稱: " . $_POST["plant_id"] . "\n簡 介: " . $_POST["introduction"] . "\n栽培特性: " . $_POST["feature"];
        $msg .= "\n品種: " . $_POST["variety"] . "\n撒種: " . $_POST["seeding"] . "\n定植: " . $_POST["feild_planting"];
        $msg .= "\n收穫: " . $_POST["harvest"] . "\n注意事項: " . $_POST["precautions"] . "\n常見問提: " . $_POST["command_isseu"];
        $msg .= "\n分類: " . $_POST["classification"] . "\n容易感染的蟲害: " . $_POST["ppest"] . "\n容易感染的病害: " . $_POST["pdisease"] . "\n提交者的email: " . $_POST["mail"]; //信件內容


        $mail->Body = $msg; //信件內容
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->AddAddress("aifarmer2022@gmail.com");   //收件者信箱

        if ($mail->Send()) {
            //    echo "信件已經發送成功。";//寄信成功就會顯示的提示訊息
            echo "<script type='text/javascript'>alert('我們已收到您的回覆，感謝您！');</script>";
        } else {
            echo "<script type='text/javascript'>alert('喔喔 失敗了～ 請再試一次');</script>";
        }
        // echo "信件發送失敗！"; //寄信失敗顯示的錯誤訊息
    }

    if (isset($_POST["Disease"])) {
        $mail->From = $_POST["mail"]; //寄件者
        $mail->FromName = $_POST["mail"]; //寄件者
        $mail->Subject =  "新增病害"; //信件標題

        $msg = "病害名稱: " . $_POST["disease"] . "\n病害簡介: " . $_POST["dintroduction"] . "\n病害特性: " . $_POST["dfeature"];
        $msg .= "\n容易發病部位: " . $_POST["daddress"] . "\n發病時期: " . $_POST["dperiod"] . "\n預防時期: " . $_POST["dpreventive_period"];
        $msg .= "\n驅逐時期: " . $_POST["deviction_period"] . "\n防治對策: " . $_POST["dpredict_policy"] . "\n藥品對策: " . $_POST["dsolution"];
        $msg .= "\n會影響的植物: " . $_POST["dplant"] . "\n提交者的email: " . $_POST["mail"]; //信件內容

        $mail->Body = $msg; //信件內容
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->AddAddress("aifarmer2022@gmail.com");   //收件者信箱

        if ($mail->Send()) {
            //    echo "信件已經發送成功。";//寄信成功就會顯示的提示訊息
            echo "<script type='text/javascript'>alert('我們已收到您的回覆，感謝您！');</script>";
        } else {
            echo "<script type='text/javascript'>alert('喔喔 失敗了～ 請再試一次');</script>";
        }
    }
    ?>
    <!-- //<form action="new.php" method="post"> -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F6F4E8;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../homepage_logout.php" style="color: #763c16;">
                <img src="../images/logo.jpg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                AI Farmer
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
                        </ul> -->
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"">
                            病蟲害知識庫
                        </a>
                        <ul class=" dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
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
                <!-- <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li> -->
                </ul>
                <!-- <a class="nav-link" href="manage.html" style="color: #1D3124;">管理</a> -->

                <!-- <button type="button" class="btn btn-outline-secondary" location.href="login.html">登入</button> -->
                <input type="button" class="btn btn-outline-secondary" value="登入" onclick="window.location = '<?php echo $login_url; ?>'">
            </div>
        </div>
    </nav>
    <div style="text-align:center;">
        </br>
        <h3>請選擇想要填寫的表單</h3>
        <p>
            <button class="btn btn-primary" onclick="myFunction('bug')" style="background-color: #CA9B66; border:1px #CA9B66 solid">害蟲</button>
            <button class="btn btn-primary" onclick="myFunction('plant')" style="background-color: #CA9B66; border:1px #CA9B66 solid">植物</button>
            <button class="btn btn-primary" onclick="myFunction('diease')" style="background-color: #CA9B66; border:1px #CA9B66 solid">病害</button>
            <!-- <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Toggle both elements</button> -->
        </p>
    </div>
    <form method='POST' action="">
        <div id="bug">
            <div class="card card-body">

                <label for="inputPest">害蟲名稱</label>
                <input type="text" name="pest" class="form-control" placeholder="ex.潛葉蠅" id="inputPest"> <!-- required> -->
                <!-- <div id="validationServerUsernameFeedback" class="invalid-feedback">
                            此為必填欄位
                        </div> -->
                </br>

                <label for="inputPIntroduction">害蟲簡介</label>
                <input type="text" name="pintroduction" class="form-control" placeholder="ex.好發於草花和蔬菜，被啃食的部位會留下有如圖畫般的白色紋路。" id="inputPIntroduction">

                </br>

                <label for="inputPFeature">害蟲特性</label>
                <input type="text" name="pfeature" class="form-control" placeholder="ex.直接觀察植物外觀時，不容易看到蟲的蹤影，但是在陽光底下，藏在葉裡的幼蟲和蛹則清晣可見。" id="inputPFeature">

                </br>

                <label for="inputPAddress">容易發病部位（請填入該害蟲容易使植物生病的部位）</label>
                <input type="text" name="paddress" class="form-control" placeholder="ex.莖、葉、花、蕾、果實" id="inputPAddress">

                </br>

                <label for="inputPPeriod">出現時期</label>
                <input type="text" name="pperiod" class="form-control" placeholder="ex.6月初—10月中" id="inputPPeriod">

                </br>

                <label for="inputPPreventivePeriod">預防時期</label>
                <input type="text" name="ppreventive_period" class="form-control" placeholder="ex.5月中—9月底" id="inputPPreventivePeriod">

                </br>

                <label for="inputPEvictionPeriod">驅逐時期</label>
                <input type="text" name="peviction_period" class="form-control" placeholder="ex.6月初—10月中" id="inputPEvictionPeriod">

                </br>

                <label for="inputPPredict">防治對策</label>
                <input type="text" name="ppredict" class="form-control" placeholder="ex.如果發現到啃食的痕跡和暗褐色的糞便，請檢視周圍環境，一旦發現有尚末潛入果實內部的幼蟲時，便可立即撲滅。若果實有洞，表示幼蟲可能潛入，必須切開果實，才能消滅幼蟲。" id="inputPPredict">

                </br>

                <label for="inputPMedicine">藥品對策</label>
                <input type="text" name="pmedicine" class="form-control" placeholder="ex.最好在幼蟲開始出沒的6月，選擇適合該種植物的殺蟲劑，仔細噴灑，連嫩葉和花也不要遺漏。蔬菜類、康乃馨、菊花等，如果遭受番茄夜蛾啃食，可使用蘇力菌等藥劑。" id="inputPMedicine">

                </br>

                <label for="inputPPlant">會影響的植物（請填入該害蟲會危害的植物）</label>
                <input type="text" name="pplant" class="form-control" placeholder="ex.山茶花、桂花、旱金蓮、甜菜根、豌豆、茼蒿、西洋芹" id="inputPPlant">

                </br>

                <label for="inputPMail">您的email</label>
                <input type="email" name="mail" class="form-control" placeholder="請填入您的電子信箱 name@example.com" id="inputPMail">

                </br>
                <div class="d-md-flex justify-content-md-center">
                    <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Pest" style="background-color: #FEDFE1;" onclick="this.form()">送出</button>
                </div>
            </div>
        </div>
        <div id="plant">
            <div class="card card-body">

                <label for="inputPlantId">植物名稱</label>
                <input type="text" name="plant_id" class="form-control" placeholder="ex.冬瓜" id="inputPlantId">

                </br>

                <label for="inputIntroduction">簡 介</label>
                <input type="text" name="introduction" class="form-control" placeholder="ex.不僅夏天能採收，還可從冬天儲存到早春，所以被稱為冬瓜。清淡的口味是各種料理的最佳材料。除可供蔬菜食用外，尚可製成冬瓜糖和冬瓜茶。" id="inputIntroduction">

                </br>

                <label for="inputFeature">栽培特性</label>
                <input type="text" name="feature" class="form-control" placeholder="ex.高溫性蔬菜，生長的最適溫度為25～30°C，於瓜類作物中生育期需時較長。" id="inputFeature">

                </br>

                <label for="inputVariety">品種</label>
                <input type="text" name="variety" class="form-control" placeholder="ex.有早生、晚生等不同的品種，一般以小型冬瓜品種較適合庭園栽培。常被栽種的品種如可供作毛瓜及小冬瓜用的「東和」、「小惠」、「清心」、「珍福」等，都屬於適合一般小家庭食用的品種。" id="inputVariety">

                </br>

                <label for="inputSeeding">撒種</label>
                <input type="text" name="seeding" class="form-control" placeholder="ex. 北部地區:2月上旬—3月底以及7月上旬—9月上旬     中、南部地區:8月中—隔年2月下旬" id="inputSeeding">

                </br>

                <label for="inputFeildPlanting">定植</label>
                <input type="text" name="feild_planting" class="form-control" placeholder="ex. 北部地區:3月底—6月初以及8月下旬—10月底     中、南部地區:10月初—隔年3月下旬" id="inputFeildPlanting">

                </br>

                <label for="inputHarvest">收穫</label>
                <input type="text" name="harvest" class="form-control" placeholder="ex. 北部地區:5月下旬—7月底以及10月中—12月中     中、南部地區:11月中—隔年8月下旬" id="inputHarvest">

                </br>

                <label for="inputPrecautions">注意事項</label>
                <input type="text" name="precautions" class="form-control" placeholder="ex.冬瓜的蔓非常容易生長，因此，常有發生莖葉變得十分脆弱、著果不良的情形。為防止這種現象，將栽培畦間擴大為約200公分，每株之間約90公分左右，並於親蔓的4~5節處進行摘心作業。" id="inputPrecautions">

                </br>

                <label for="inputCommandIsseu">常見問題</label>
                <input type="text" name="command_isseu" class="form-control" placeholder="ex.Q.定植之後的生長情況不好  A.早點整理田地，事先準備好畦並澆水，進行鋪設塑膠布處理定植之後的生長變惡劣，主要的原因應是定植時根部受到嚴重傷害，或是地溫太低......" id="inputCommandIsseus">

                </br>

                <label for="inputPPest">容易感染的蟲害</label>
                <input type="text" name="ppest" class="form-control" placeholder="ex.無" id="inputPPest">

                </br>

                <label for="inputPDisease">容易感染的病害</label>
                <input type="text" name="pdisease" class="form-control" placeholder="ex.蔓割病" id="inputPDisease">

                </br>
                </br>

                <select class="form-select col-sm-1 col-form-label text-center" aria-label="Default select example" name="classification">
                    <option selected>未選擇分類</option>
                    <option value="水果">花卉</option>
                    <option value="蔬菜">蔬菜</option>
                    <option value="水果">水果</option>
                    <option value="其他">其他</option>
                </select>

                </br>

                <label for="inputMail">您的email</label>
                <input type="email" name="mail" class="form-control" placeholder="請填入您的電子信箱 name@example.com" id="inputMail">

                </br>
                <div class="d-md-flex justify-content-md-center">
                    <button type="submit" class="btn btn-outline-secondary text-color=$red-300" name="Plant" style="background-color: #FEDFE1;" onclick="this.form.submit()">送出</button>
                </div>


            </div>
        </div>


        <div id="diease">
            <div class="card card-body">

                <label for="inputDisease">病害名稱</label>
                <input type="text" name="disease" class="form-control" placeholder="ex.基腐病" id="inputDisease">

                </br>

                <label for="inputDIntroduction">病害簡介</label>
                <input type="text" name="dintroduction" class="form-control" placeholder="ex.發病與鬱金香、小蒼蘭、百合等球根植物的傳染病。起因是球根、莖、塊莖和鱗莖等被土壤中的菌絲侵襲，導致莖葉產生黃化、枯萎的疾病。 " id="inputDIntroduction">

                </br>

                <label for="inputDFeature">病害特性</label>
                <input type="text" name="dfeature" class="form-control" placeholder="ex.染病的植物葉子一般會從外側逐漸黃化。一旦受到病原菌感染，即使將球根掘起，病情還是會繼續惡化，最後乾枯成木乃伊，連子球也難以倖免。" id="inputDFeature">

                </br>

                <label for="inputDAddress">容易發病部位</label>
                <input type="text" name="daddress" class="form-control" placeholder="ex.根、球根、莖、葉" id="inputDAddrss">

                </br>

                <label for="inputDPeriod">發病時期</label>
                <input type="text" name="dperiod" class="form-control" placeholder="ex.4月初—5月底" id="inputDPeriod">

                </br>

                <label for="inputDPreventivePeriod">預防時期</label>
                <input type="text" name="dpreventive_period" class="form-control" placeholder="ex.（種植球根之前）9月中—11月底" id="inputDPreventivePeriod">

                </br>

                <label for="inputDEvictionPeriod">驅逐時期</label>
                <input type="text" name="deviction_period" class="form-control" placeholder="ex.4月初—5月底" id="inputDEvictionPeriod">

                </br>
                <label for="inputDPredictPolicy">防治對策</label>
                <input type="text" name="dpredict_policy" class="form-control" placeholder="ex.購買球根時，記得避開表面已經長出褐色斑點、長出黴菌、有部分腐爛的個體。發現植株發病時，要連同球根將周圍的土壤一併挖起來丟棄。因為病原菌會殘留於土壤中，如果... " id="inputDPredictPolicy">

                </br>
                <label for="inputDSolution">藥品對策</label>
                <input type="text" name="dsolution" class="form-control" placeholder="ex.如果是栽培鬱金香或洋蔥苗，可以先把球根浸泡在免賴得殺菌劑再種植。" id="inputDSolution">

                </br>
                <label for="inputDPlant">會影響的植物</label>
                <input type="text" name="dplant" class="form-control" placeholder="ex.鬱金香、唐菖蒲、水仙、百合、番紅花、小蒼蘭、蓮花、馬鈴薯、洋蔥、芋頭、韭菜" id="inputDPlant">

                </br>

                <label for="inputDMail">您的email</label>
                <input type="email" name="mail" class="form-control" placeholder="請填入您的電子信箱 name@example.com" id="inputDMail">

                </br>
                <div class="d-md-flex justify-content-md-center">
                    <button type="submit" class="btn" name="Disease" style="background-color: #FEDFE1;" onclick="this.form()">送出</button>
                </div>
            </div>

        </div>
    </form>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        function ini() {
            var x = document.getElementById("bug");
            x.style.display = "none";
            var x = document.getElementById("plant");
            x.style.display = "none";
            var x = document.getElementById("diease");
            x.style.display = "none";

        }

        function myFunction(thing) {
            //  alert(thing);
            switch (thing) {
                case 'bug':
                    var x = document.getElementById("bug");
                    x.style.display = "block";

                    var y = document.getElementById("plant");
                    y.style.display = "none";
                    var z = document.getElementById("diease");
                    z.style.display = "none";
                    break;
                case 'plant':
                    var x = document.getElementById("bug");
                    x.style.display = "none";
                    var y = document.getElementById("plant");
                    y.style.display = "block";
                    var z = document.getElementById("diease");
                    z.style.display = "none";
                    break;
                case 'diease':
                    var x = document.getElementById("bug");
                    x.style.display = "none";
                    var y = document.getElementById("plant");
                    y.style.display = "none";
                    var z = document.getElementById("diease");
                    z.style.display = "block";
                    break;
            }
        }
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>