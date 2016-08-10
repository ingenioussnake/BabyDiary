<?php
    include './dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "appetite";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'appetite':
            echo getAppetite(isset($_GET["unit"]) ? $_GET["unit"] : "day");
            break;

        case 'count':
            echo getAppetiteCount();
            break;

        case 'sleep':
            echo getSleep(isset($_GET["unit"]) ? $_GET["unit"] : "day");
            break;

        case 'stature':
            echo getStature();
            break;
    }
    $dba->disconnect();

    function getAppetite ($unit) {
        global $dba;
        $unit_cal = getUnitCalculation($unit, "dining");
        $stmt = "select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm left join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." union select mm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm right join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." order by ".$unit." desc limit 0, 20;";
        return json_encode($dba->query($stmt));
    }

    function getAppetiteCount () {
        return json_encode(array(
            "day" => _getAppetiteCount("day"),
            "week" => _getAppetiteCount("week"),
            "month" => _getAppetiteCount("month")
        ));
    }

    function _getAppetiteCount ($unit) {
        global $dba;
        if ($unit == "week") {
            $unit_cal = "datediff(CURRENT_DATE, date) < 7";
        } else if ($unit == "month") {
            $unit_cal = "datediff(CURRENT_DATE, date) < 30";
        } else {
            $unit_cal = "dayofyear(CURRENT_DATE) = dayofyear(date)";
        }
        return array_map(function($item)use($unit_cal){
            return _getAppetiteCount_($unit_cal, $item);
        }, array("mm", "fm", "mx"));
    }

    function _getAppetiteCount_ ($unit_cal, $type) {
        global $dba;
        $stmt = "select count(*) as count from dining where food = (select id from food where name='".$type."') and year(CURRENT_DATE) = year(date) and ".$unit_cal." and baby = 1";
        return $dba->query($stmt, function($row){
            return $row["count"];
        })[0];
    }

    function getSleep ($unit) {
        global $dba;
        $unit_cal = getUnitCalculation($unit, "sleep");
        $stmt = "SELECT ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from sleep where baby = 1 group by ".$unit." order by ".$unit." desc limit 0, 15";
        return json_encode($dba->query($stmt));
    }

    function getStature () {}

    function getUnitCalculation ($unit, $table) {
        $unit_cal = "";
        if ($unit == "week") {
            $unit_cal = "ceiling(datediff(date, (select birthday from baby where ".$table.".baby = id)) / 7)";
        } else if ($unit == "month") {
            $unit_cal = "month(date)";
        } else {
            $unit_cal = "date";
        }
        return $unit_cal;
    }
?>