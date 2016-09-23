<?php
    include "./bootstrap.php";
    echo $twig->render("register.html", array(
        "title"      => "用户注册",
        "page_title" => "用户注册"
    ));
?>