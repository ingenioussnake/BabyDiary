<?php
    include './dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "appetite";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'appetite':
            echo getAppetite();
            /*if ($_GET["food"] == "mm") {
                echo getMMAppetite();
            } else {
                echo getFMAppetite();
            }*/
            break;

        case 'stature':
            echo getStature();
            break;
    }
    $dba->disconnect();

    function getAppetite () {
        return json_encode(array("mm"=>getMMAppetite(), "fm"=>getFMAppetite()));
    }

    function getMMAppetite () {
        global $dba;
        return $dba->query("SELECT date, SUM(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 AS duration FROM dining WHERE baby = 1 AND food IN (SELECT id from food where name = 'mm' OR name = 'mx') GROUP BY date ORDER BY date DESC LIMIT 0, 30;");
    }

    function getFMAppetite () {
        global $dba;
        return $dba->query("SELECT date, SUM(appetite) AS appetite FROM dining WHERE baby = 1 AND food IN (SELECT id from food where name = 'fm' OR name = 'mx') GROUP BY date ORDER BY date DESC LIMIT 0, 30;");
    }

    function getStature () {}
?>