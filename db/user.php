<?php
    if (!isset($_GET["type"])) {
        echo "wrong data";
        exit;
    }
    include './dba.php';
    $type = $_GET["type"];
    $dba = new DBA();
    $dba->connect();

    if ($type == "login") {
        echo login($_POST["username"], $_POST["password"]);
    } else if ($type == "reg") {
        echo register($_POST);
    }
    $dba->disconnect();

    function login ($username, $password) {
        global $dba;
        $user = $dba->query("SELECT username FROM user WHERE username = '". $username ."' and password = '". md5($password) ."';", function($row){ return $row["username"]; });
        if (count($user) > 0) {
            auth_add($user[0]);
            return true;
        } else {
            return true;
        }
    }

    function register ($data) {
        global $dba;
        $file = $_FILES["baby_avatar"]["tmp_name"];
        if (!is_uploaded_file($file)) {
            echo 0;
            return;
        }
        $avatar = addslashes(file_get_contents($file));
        $result = $dba->exec("INSERT INTO user(username, password) VALUES ('".$data['username']."', '".$data['password']."')") &&
                $dba->exec("INSERT INTO baby(id, parent, name, birthday, avatar, blood, sex, weight, height) VALUES
                          (NULL, '".$data['username']."', '".$data['baby_name']."', '".$data['baby_birthday']."', '".$avatar."', '".$data['baby_blood']."', '".$data['baby_sex']."', '".$data['baby_weight']."', '".$data['baby_height']."')");
        if ($result) {
            auth_add($data['username']);
            return 1;
        } else {
            return 0;
        }
    }
?>