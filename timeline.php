<?php
    include "./bootstrap.php";
    do_auth_check();
    echo $twig->render("timeline.html", array(
        "title"      => "宝宝的一天",
        "page_title" => ""
    ));
?>