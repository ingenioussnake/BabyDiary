<?php
    if (!isset($_GET["type"])) {
        echo "wrong get";
        exit;
    }
    include './dba.php';
    $type = $_GET["type"];
    $dba = new DBA();
    $dba->connect();
    $baby_id = getBaby();
    switch ($type) {
        case 'date':
            echo getDates($_GET["offset"], $_GET["size"]);
            break;

        case 'list':
            echo getList($_GET["date"]);
            break;
    }
    $dba->disconnect();

    function getDates ($offset, $size) {
        global $dba;
        $tables = array("dining", "sleep", "shit", "memo", "height", "weight");
        $stmt = implode(" UNION ", array_map(function($table){
            global $baby_id;
            return "SELECT date FROM ". $table . " where baby = ". $baby_id;
        }, $tables)) . " ORDER BY date DESC LIMIT " . $offset . ", " . $size . ";";
        $result = $dba->query($stmt, function ($row) { return $row["date"];});
        return json_encode($result);
    }

    function getList ($date) {
        $actions = array("sleep", "shit", "height", "weight");
        $results = array();
        global $dba, $baby_id;
        foreach ($actions as $action) {
            $result = $dba->query("select * from " . $action . " WHERE baby = ".$baby_id." AND date = '" . $date. "';", function($row) use ($action) {
                return array("action"=>$action, "item"=>$row);
            });
            $results = array_merge($results, $result);
        }
        $results = array_merge($results, getDiningByDate($date), getMemoByDate($date));
        return json_encode($results);
    }

    function getDiningByDate ($date) {
        global $dba, $baby_id;
        return $dba->query("select dining.id, dining.date, dining.start, dining.end, dining.appetite, dining.comment, food.name as food from dining inner join food on dining.food = food.id WHERE dining.baby = ".$baby_id." AND dining.date = '" . $date. "' order by dining.start desc;", function($row) {
            return array("action"=>"dining", "item"=>$row);
        });
    }

    function getMemoByDate ($date) {
        global $dba, $baby_id;
        $result = $dba->query("SELECT * FROM memo WHERE baby = ".$baby_id." AND date = '". $date. "';", function($row){
            global $dba;
            $pics = $dba->query("SELECT id FROM picture WHERE memo = ". $row["id"] .";", function($pic){
                return $pic["id"];
            });
            $row["pictures"] = $pics;
            return array("action"=>"memo", "item"=>$row);
        });
        return $result;
    }
?>