<?php
    include './dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "appetite";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'appetite':
            echo getAppetite(isset($_GET["unit"]) ? $_GET["unit"] : "day");
            break;

        case 'stature':
            echo getStature();
            break;
    }
    $dba->disconnect();

    function getAppetite ($unit) {
        global $dba;
        if ($unit == "week") {
            $unit_cal = "ceiling(datediff(date, (select birthday from baby where dining.baby = id)) / 7)";
        } else if ($unit == "month") {
            $unit_cal = "month(date)";
        } else {
            $unit_cal = "date";
        }        
        $stmt = "select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm left join (select ".$unit_cal." as ".$unit.", sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." union select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm right join (select ".$unit_cal." as ".$unit.", sum(TIME_TO_SEC(TIMEDIFF(end, start))) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." order by ".$unit." desc limit 0, 7;";
        return json_encode($dba->query($stmt));
    }

    function getStature () {}
?>