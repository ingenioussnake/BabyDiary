<?php
    require_once './vendor/autoload.php';
    require_once './auth.php';

    $twig_loader = new Twig_Loader_Filesystem("./templates");
    $twig = new Twig_Environment($twig_loader/*, array(
        'cache' => './cache'
    )*/);
?>