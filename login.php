<?php
    include "./bootstrap.php";
    $error = false;
    $auth = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = array_map(function($item){return test_input($item);}, $_POST);
        if (login($data)) {
            auth_add($data['username']); //cannot added during login
            $auth = true;
        } else {
            $error = true;
        }
    } else {
        $auth = auth_check();
    }
    if ($auth) {
        header("Location: ./index.php");
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function login ($data) {
        $ch = curl_init(getUserUrl());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function getUserUrl () {
        $paths = explode("/", $_SERVER['PHP_SELF']);
        $paths[count($paths) - 1] = "db/user.php?type=login";
        return "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].implode("/", $paths);
    }

    echo $twig->render("login.html", array(
        "title"      => "用户登录",
        "page_title" => "用户登录",
        "error"      => $error,
        "self"       => $_SERVER["PHP_SELF"]
    ));
?>