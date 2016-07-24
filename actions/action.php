<?php
    if (!isset($_POST["type"]) || !isset($_POST["data"])) {
        echo "wrong data";
        exit;
    }
    include '../db/dba.php';
    $type = $_POST["type"];
    $data = $_POST["data"];
    $dba = new DBA();
    $dba->connect();
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

        case 'height':
            echo insertHeight($data);
            break;

        case 'weight':
            echo insertWeight($data);
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
    $dba->disconnect();

    function insertDining ($data) {
        global $dba;
        return $dba->exec("INSERT INTO dining (id, baby, date, start, end, mm) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["start"] . "', '". $data["end"] . "', " . $data["mm"]. ")");
    }

    function insertSleep ($data) {
        global $dba;
        return $dba->exec("INSERT INTO sleep (id, baby, date, start, end) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["start"] . "', '". $data["end"] . "')");
    }

    function insertShit ($data) {
        global $dba;
        return $dba->exec("INSERT INTO shit (id, baby, date, time) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["time"]. "')");
    }

    function insertHeight ($data) {
        global $dba;
        return $dba->exec("INSERT INTO height (id, baby, date, time, height) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["time"] . "', '" . $data["height"]. "')");
    }

    function insertWeight ($data) {
        global $dba;
        return $dba->exec("INSERT INTO weight (id, baby, date, time, weight) VALUES (NULL, 1, '". $data["date"] . "', '" . $data["time"] . "', '" . $data["weight"]. "')");
    }

    function updateAction ($data) {
        global $dba;
        $set = "";
        foreach ($data as $key => $value) {
            if ($key != "id" && $key != "type" && $key != "baby") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        return $dba->exec("UPDATE ". $data["type"]. " SET " . $set . " WHERE id = ". $data["id"]);
    }

    function removeAction ($data) {
        global $dba;
        return $dba->exec("DELETE FROM ". $data["type"]. " WHERE id = ". $data["id"]);
    }
?>