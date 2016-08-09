<?php
    include './dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "appetite";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'appetite':
            echo getAppetite(isset($_GET["unit"]) ? $_GET["unit"] : "day");
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

    function getAppetite ($unit) {
        // return json_encode(array("mm"=>getMMAppetite(), "fm"=>getFMAppetite()));
        global $dba;
        if ($unit == "week") {
            $unit_cal = "ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7)";
        } else if ($unit == "month") {
            $unit_cal = "month(date)";
        } else {
            $unit_cal = "date";
        }        
        $stmt = "select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm left join (select ".$unit_cal." as ".$unit.", sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." union select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm right join (select ".$unit_cal." as ".$unit.", sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." order by ".$unit." desc limit 0, 7;";
        /*$stmt = "";
        if ($unit == "week") {
            $stmt = "select fm.week, fm.appetite, mm.duration from (select ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7) as week, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by week) as fm left join (select ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7) as week, sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by week) as mm on fm.week = mm.week union select fm.week, fm.appetite, mm.duration from (select ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7) as week, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by week) as fm right join (select ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7) as week, sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by week) as mm on fm.week = mm.week order by week desc limit 0, 7;";
        } else if ($unit == "month") {
            $stmt = "select fm.month, fm.appetite, mm.duration from (select month(date) as month, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by month) as fm left join (select month(date) as month, sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by month) as mm on fm.month = mm.month union select fm.month, fm.appetite, mm.duration from (select month(date) as month, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by month) as fm right join (select month(date) as month, sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where food in (select id from food where name='mm' or name='mx') group by month) as mm on fm.month = mm.month order by month desc limit 0, 7;";
        } else {
            $stmt = "select fm.date, fm.appetite, mm.duration from (select date, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by date) as fm left join (select date, SUM(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 AS duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by date) as mm on fm.date = mm.date union select fm.date, fm.appetite, mm.duration from (select date, sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by date) as fm right join (select date, SUM(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 AS duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by date) as mm on fm.date = mm.date order by date desc limit 0, 7;";
        }*/
        return json_encode($dba->query($stmt));
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