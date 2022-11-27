<?php
class Connect extends PDO
{
    public function __construct()
    {
        parent::__construct(
            "mysql:host=localhost;dbname=aifarmer",
            'aifarmer',
            '1234',
            //"mysql:host=localhost;dbname=aifarmer",
            //'root',
            //'',
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        // $this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}
class Controller
{
    //insert data
    function insertData($data)
    {
        $db = new Connect;
        $checkUser = $db->prepare("SELECT * FROM google_users WHERE email= :email");
        $checkUser->execute(['email' => $data["email"]]);
        $info = $checkUser->fetch(PDO::FETCH_ASSOC);


        if ($data["email"] == 'aifarmer2022@gmail.com') {
            // $insertUser = $db->prepare("INSERT INTO google_users (email, id) VALUES ('" . $data["email"] . "', '" . $data["id"] . "')");
            session_start();
            $_SESSION['email'] =  $data['email'];
            header('Location: gmanagement/manage.php');
        } else {
            if (!$info["id"]) {
                // $session = $this -> generateCode(10);

                $insertUser = $db->prepare("INSERT INTO google_users (email, id) VALUES ('" . $data["email"] . "', '" . $data["id"] . "')");
                session_start();
                $_SESSION['id'] =  $data['id'];
                $_SESSION['email'] =  $data['email'];
                header('Location: username.php');
                $insertUser->execute([
                    '".$data["email"]."' => $data["email"],
                    '".$data["id"]."'  => $data["id"],
                    // ':session_ ' => $session,"
                    // ':passward_' => $this -> generateCode(5)

                ]);
                if ($insertUser) {
                    setcookie("id", $db->lastInsertId(), time() + 60 * 60 * 24 * 30, "/", NULL);
                    // setcookie("sess", $session, time()+60*60*24*30, "/", NULL);
                    header('Location: username.php');
                    exit();
                } else {
                    return "Error inserting user!";
                }
            } else {
                session_start();
            
                $_SESSION['email'] =  $data['email'];
                setcookie("id", $info['id'], time() + 60 * 60 * 24 * 30, "/", NULL);
                // setcookie("sess", $info["session"], time()+60*60*24*30, "/", NULL);
                header('Location: greenhouse_control/greenhouse_main.php');
                exit();
            }
        }
    }
}
