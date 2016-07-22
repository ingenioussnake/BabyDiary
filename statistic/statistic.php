<?php
    if (!isset($_GET["type"])) {
        echo "wrong get";
        exit;
    }
    $type = $_GET["type"];
    switch ($type) {
        case 'date':
            echo getDates();
            break;

        case 'list':
            echo getList($_GET["date"]);
            break;
    }

    function getDates () {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $result = $mysqli->query("select date from dining UNION select date from sleep UNION select date from shit");
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row["date"]);
        }
        $mysqli->close();
        return json_encode($rows);
    }

    function getList ($date) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $types = array("dining", "sleep", "shit");
        $rows = array();
        foreach ($types as $type) {
            $result = $mysqli->query("select * from " . $type . " WHERE baby = 1 AND date = '" . $date. "'");
            while ($row = $result->fetch_assoc()) {
                $row["type"] = $type;
                array_push($rows, $row);
            }
        }
        $mysqli->close();
        return json_encode($rows);
    }
?>