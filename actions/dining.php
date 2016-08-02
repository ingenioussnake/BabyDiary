<?php
    include '../db/dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "insert";
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'update':
            echo updateDining();
            break;

        case 'remove':
            echo removeDining();
            break;

        case 'insert':
            echo insertDining();
            break;
    }
    $dba->disconnect();

    function insertDining () {
        global $dba;
        $columns = "id, baby";
        $values = "NULL, 1";
        $_POST["food"] = getFoodId($_POST["food"]);
        foreach ($_POST as $key => $value) {
            $columns .= ", " . $key;
            $values .= ", '" . $value . "'";
        }
        $stmt = "INSERT INTO dining (" . $columns . ") VALUES (" . $values . ")";
        return $dba->exec($stmt);
    }

    function updateDining () {
        global $dba;
        $set = "";
        $_POST["food"] = getFoodId($_POST["food"]);
        foreach ($_POST as $key => $value) {
            if ($key != "id") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        return $dba->exec("UPDATE dining SET " . $set . " WHERE id = ". $_POST["id"]);
    }

    function removeDining () {
        global $dba;
        return $dba->exec("DELETE FROM dining WHERE id = ". $_POST["id"]);
    }

    function getFoodId ($name) {
        global $dba;
        $result = $dba->query("SELECT ID from food WHERE name = '". $name ."';", function($row){
            return $row["ID"];
        });
        return $result[0];
    }
?>