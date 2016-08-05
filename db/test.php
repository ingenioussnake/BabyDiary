<?php
    include '../config.php';
    $root = get_config("../php.ini")["picture_location"];
    $dir = prepareDestination($root, 1, date("Y-m-d"));
    $path = $root.$dir;
echo dirname(__FILE__);
echo "<br />";
echo fileperms($path);
echo "<br />";


    function prepareDestination ($root, $baby_id, $date) {
        if(!file_exists($root)) {
            mkdir($root);
        }
        $dir = $baby_id."/";
        if(!file_exists($root.$dir)) {
            mkdir($root.$dir);
        }
        $dir .= $date."/";
        if(!file_exists($root.$dir)) {
            mkdir($root.$dir);
        }
        return $dir;
    }
?>