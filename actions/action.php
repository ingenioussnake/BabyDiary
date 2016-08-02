<?php
    if (!isset($_GET["type"]) || !isset($_GET["action"])) {
        echo "wrong data";
        exit;
    }
    include '../db/dba.php';
    $type = $_GET["type"];
    $table = $_GET["action"];
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'update':
            echo updateAction($table);
            break;

        case 'remove':
            echo removeAction($table);
            break;

        case 'insert':
            echo insert($table);
            break;
    }
    $dba->disconnect();

    function insert ($table) {
        global $dba;
        $columns = "id, baby";
        $values = "NULL, 1";
        foreach ($_POST as $key => $value) {
            $columns .= ", " . $key;
            $values .= ", '" . $value . "'";
        }
        $stmt = "INSERT INTO " . $table . "(" . $columns . ") VALUES (" . $values . ")";
        return $dba->exec($stmt);
    }

    function updateAction ($table) {
        global $dba;
        $set = "";
        foreach ($_POST as $key => $value) {
            if ($key != "id") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        return $dba->exec("UPDATE ". $table. " SET " . $set . " WHERE id = ". $_POST["id"]);
    }

    function removeAction ($table) {
        global $dba;
        return $dba->exec("DELETE FROM ". $table. " WHERE id = ". $_POST["id"]);
    }
?>