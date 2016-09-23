<?php
    include "./bootstrap.php";
    do_auth_check();
    echo $twig->render("index.html", array(
        "title"      => "宝宝日记",
        "page_title" => "宝宝日记"
    ));
?>