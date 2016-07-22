<?php
    if (!isset($_POST["type"]) || !isset($_POST["data"])) {
        echo "wrong data";
        exit;
    }
    $type = $_POST["type"];
    $data = $_POST["data"];
    switch ($type) {
        case 'dining':
            echo insertDining($data);
            break;
        
        case 'sleep':
            echo insertSleep($data);
            break;

        case 'shit':
            echo insertShit($data);
            break;

        case 'update':
            echo updateAction($data);
            break;

        case 'remove':
            echo removeAction($data);
            break;

        default:
            # code...
            break;
    }

    function insertDining ($data) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $result = $mysqli->query("INSERT INTO dining (id, baby, date, start, end, mm) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["start"] . "', '". $data["end"] . "', " . $data["mm"]. ")");
        $mysqli->close();
        return $result;
    }

    function insertSleep ($data) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $result = $mysqli->query("INSERT INTO sleep (id, baby, date, start, end) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["start"] . "', '". $data["end"] . "')");
        $mysqli->close();
        return $result;
    }

    function insertShit ($data) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $result = $mysqli->query("INSERT INTO shit (id, baby, date, time) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["time"]. "')");
        $mysqli->close();
        return $result;
    }

    function updateAction ($data) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $set = "";
        foreach ($data as $key => $value) {
            if ($key != "id" && $key != "type" && $key != "baby") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        echo "UPDATE ". $data["type"]. " SET " . $set . " WHERE id = ". $data["id"];
        $result = $mysqli->query("UPDATE ". $data["type"]. " SET " . $set . " WHERE id = ". $data["id"]);
        $mysqli->close();
        return $result;
    }

    function removeAction ($data) {
        $mysqli = new mysqli("localhost", "baby", "leed", "baby_diary");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $result = $mysqli->query("DELETE FROM ". $data["type"]. " WHERE id = ". $data["id"]);
        $mysqli->close();
        return $result;
    }
?>