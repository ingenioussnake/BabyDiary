<?php
    include "./bootstrap.php";
    do_auth_check();
    echo $twig->render("action.html", array(
        "title"      => "有事情喽",
        "page_title" => ""
    ));
?>