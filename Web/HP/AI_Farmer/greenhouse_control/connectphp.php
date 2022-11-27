<?php
        $server_name = '192.192.156.30';
        $username = 'aifarmer';
        $password = '1234';
        $db_name = 'aifarmer';

        // mysqli 的四個參數分別為：伺服器名稱、帳號、密碼、資料庫名稱
        $conn = new mysqli($server_name, $username, $password, $db_name);

        if (!empty($conn->connect_error)) {
            die('資料庫連線錯誤:' . $conn->connect_error);    // die()：終止程序
        }
