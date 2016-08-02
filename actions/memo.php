<?php
    include '../db/dba.php';
    $type = isset($_GET["type"]) ? $_GET["type"] : "insert";
    $imageType = array(
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/pjpeg',
        'image/gif',
        'image/bmp',
        'image/x-png'
    );
    define("MAX_PIC_SIZE", 5300000);
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'update':
            echo updateMemo();
            break;

        case 'remove':
            echo removeMemo();
            break;

        case 'insert':
            echo insertMemo();
            break;
    }
    $dba->disconnect();

    function insertMemo () {
        global $dba;
        $id = _insertMemo();
        if (isset($_FILES["picture"])) {
            $pictures = $_FILES["picture"];
            insertPictures($id, $pictures);
        }
        return 1;
    }

    function _insertMemo () {
        global $dba;
        $stmt = "INSERT INTO memo VALUES (NULL, 1, '" . $_POST["date"] . "', '" . $_POST["time"] . "', '" . $_POST["title"] . "', '" . $_POST["memo"] . "');";
        $dba->exec($stmt);
        return $dba->insert_id();
    }

    function insertPictures ($id, $pictures) {
        global $dba, $imageType;
        $names = $pictures["name"];
        $size = $pictures["size"];
        $tmp = $pictures["tmp_name"];
        $type = $pictures["type"];
        $length = count($names);
        for ($i = 0; $i < $length; $i++) {
            if (is_uploaded_file($tmp[$i]) && $size[$i] < MAX_PIC_SIZE && in_array($type[$i], $imageType)) {
                $pic = addslashes(fread(fopen($tmp[$i], 'rb'), filesize($tmp[$i])));
                $dba->exec("INSERT INTO picture VALUES (NULL, ". $id . ", '" . $pic . "')");
            }
        }
    }

    function updateMemo () {
        global $dba;
        $set = "";
        foreach ($_POST as $key => $value) {
            if ($key != "id") {
                $set .= $key . " = '" . $value . "', ";
            }
        }
        $set = substr($set, 0, -2); // remove the last ", "
        return $dba->exec("UPDATE memo SET " . $set . " WHERE id = ". $_POST["id"]);
    }

    function removeMemo () {
        global $dba;
        return $dba->exec("DELETE FROM memo WHERE id = ". $_POST["id"]);
    }
?>