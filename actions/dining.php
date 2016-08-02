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
            echo updateDining($data);
            break;

        case 'remove':
            echo removeDining($data);
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
        $data["food"] = getFoodId($data["food"]);
        foreach ($data as $key => $value) {
            $columns .= ", " . $key;
            $values .= ", '" . $value . "'";
        }
        $stmt = "INSERT INTO " . $type . "(" . $columns . ") VALUES (" . $values . ")";
        return $dba->exec($stmt);
    }

    function updateDining ($data) {
        global $dba;
        $set = "";
        $data["food"] = getFoodId($data["food"]);
        foreach ($data as $key => $value) {
            if ($key != "id" && $key != "type" && $key != "baby") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        return $dba->exec("UPDATE ". $data["type"]. " SET " . $set . " WHERE id = ". $data["id"]);
    }

    function removeDining ($data) {
        global $dba;
        return $dba->exec("DELETE FROM ". $data["type"]. " WHERE id = ". $data["id"]);
    }

    function getFoodId ($name) {
        global $dba;
        $result = $dba->query("SELECT ID from food WHERE name = '". $name ."';", function($row){
            return $row["ID"];
        });
        return $result[0];
    }
?>