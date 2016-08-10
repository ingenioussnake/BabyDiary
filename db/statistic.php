<?php
    include './dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "appetite";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'appetite':
            echo getAppetite();
            break;

        case 'count':
            echo getAppetiteCount();
            break;

        case 'sleep':
            echo getSleep();
            break;

        case 'height':
        case 'weight':
            echo getGrowth();
            break;

        case 'comp':
            echo getCompGrowth();
            break;
    }
    $dba->disconnect();

    function getAppetite () {
        global $dba;
        $unit = $_GET["unit"];
        $offset = $_GET["offset"];
        $size = $_GET["size"];
        $unit_cal = getUnitCalculation($unit, "dining");
        $stmt = "select fm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm left join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." union select mm.".$unit.", fm.appetite, mm.duration from (select ".$unit_cal." as ".$unit.", sum(appetite) as appetite from dining where baby = 1 and food in (select id from food where name='fm' or name='mx') group by ".$unit.") as fm right join (select ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from dining where baby = 1 and food in (select id from food where name='mm' or name='mx') group by ".$unit.") as mm on fm.".$unit." = mm.".$unit." order by ".$unit." desc limit ".$offset.", ".$size.";";
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

    function getSleep () {
        global $dba;
        $unit = $_GET["unit"];
        $offset = $_GET["offset"];
        $size = $_GET["size"];
        $unit_cal = getUnitCalculation($unit, "sleep");
        $stmt = "SELECT ".$unit_cal." as ".$unit.", sum((time_to_sec(timediff(end, start)) + 86400) % 86400) / 60 as duration from sleep where baby = 1 group by ".$unit." order by ".$unit." desc limit ".$offset.", ".$size.";";
        return json_encode($dba->query($stmt));
    }

    function getGrowth () {
        global $dba;
        $type = $_GET["type"];
        $unit = $_GET["unit"];
        $unit_cal = getUnitCalculation($unit, $type, true);
        $stmt = "select ".$unit_cal." as ".$unit.",  MAX(".$type.") as ".$type." from ".$type." where baby = 1 group by ".$unit." order by ".$unit.";";
        $result = $dba->query($stmt);
        if ($_GET["flag"] == "true") {
            //
        }
        return json_encode($result);
    }

    function getCompGrowth () {
        global $dba;
        $stmt = "select weight.weight as weight,  height.height as height from height, weight where height.baby = 1 and weight.baby = 1 and height.date = weight.date order by height asc";
        $result = $dba->query($stmt);
        return json_encode($result);
    }

    function getUnitCalculation ($unit, $table, $since = false) {
        $unit_cal = "";
        if ($unit == "week") {
            $unit_cal = "ceiling(datediff(date, (select birthday from baby where ".$table.".baby = id)) / 7)";
        } else if ($unit == "month") {
            $unit_cal = $since ? "ceiling(datediff(date, (select birthday from baby where ".$table.".baby = id)) / 30)" : "month(date)";
        } else {
            $unit_cal = "date";
        }
        return $unit_cal;
    }
?>