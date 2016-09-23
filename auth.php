<?php

    $lifeTime = 3 * 24 * 3600;
    session_set_cookie_params($lifeTime);
    if (isset($_COOKIE["PHPSESSID"])) {
        session_id($_COOKIE["PHPSESSID"]);
    }
    session_start();

    function auth_check () {
        return isset($_SESSION["user"]) && isset($_SESSION["login"]) && $_SESSION["login"] === true;
    }

    function auth_add ($user) {
        $_SESSION["user"] = $user;
        $_SESSION["login"] = true;
    }

    function auth_destroy ($user) {
        unset($_SESSION["user"]);
        unset($_SESSION["login"]);
        session_destroy();
    }

    function do_auth_check () {
        if (!auth_check()) {
            header("Location: /login.php");
        }
    }
?>