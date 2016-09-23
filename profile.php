<?php
    include "./bootstrap.php";
    do_auth_check();
    echo $twig->render("profile.html", array(
        "title"      => "宝宝档案",
        "page_title" => "宝宝档案"
    ));
?>