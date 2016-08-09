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
        $stmt = "select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm left join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." union select mm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm right join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." order by ".$unit." desc limit 0, 7;";
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
            $unit_cal = "week(CURRENT_DATE) = week(date)";
        } else if ($unit == "month") {
            $unit_cal = "month(CURRENT_DATE) = month(date)";
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

    function getStature () {}
?>