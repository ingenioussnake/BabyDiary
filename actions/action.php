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
        case 'update':
            echo updateAction($data);
            break;

        case 'remove':
            echo removeAction($data);
            break;

        default:
            echo insert($type, $data);
            break;
    }
    $dba->disconnect();

    function insert ($type, $data) {
        global $dba;
        $columns = "id, baby";
        $values = "NULL, 1";
        foreach ($data as $key => $value) {
            $columns .= ", " . $key;
            $values .= ", '" . $value . "'";
        }
        $stmt = "INSERT INTO " . $type . "(" . $columns . ") VALUES (" . $values . ")";
        return $dba->exec($stmt);
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