<?php
    if (!isset($_POST["title"])) {
        echo "title required";
        exit;
    }
    include '../db/dba.php';
    $imageType = array(
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/pjpeg',
        'image/gif',
        'image/bmp',
        'image/x-png'
    );
    $maxSize = 5300000;
    $dba = new DBA();
    $dba->connect();
    echo insert();
    $dba->disconnect();

    function insert () {
        global $dba;
        $id = insertMemo();
        if (isset($_FILES["picture"])) {
            $pictures = $_FILES["picture"];
            insertPictures($id, $pictures);
        }
        return 1;
    }

    function insertMemo () {
        global $dba;
        $stmt = "INSERT INTO memo VALUES (NULL, 1, '" . $_POST["date"] . "', '" . $_POST["time"] . "', '" . $_POST["title"] . "', '" . $_POST["memo"] . "');";
        $dba->exec($stmt);
        return $dba->insert_id();
    }

    function insertPictures ($id, $pictures) {
        global $dba, $imageType, $maxSize;
        $names = $pictures["name"];
        $size = $pictures["size"];
        $tmp = $pictures["tmp_name"];
        $type = $pictures["type"];
        $length = count($names);
        for ($i = 0; $i < $length; $i++) {
            if (is_uploaded_file($tmp[$i]) && $size[$i] < $maxSize && in_array($type[$i], $imageType)) {
                $pic = addslashes(fread(fopen($tmp[$i], 'rb'), filesize($tmp[$i])));
                $dba->exec("INSERT INTO picture VALUES (NULL, ". $id . ", '" . $pic . "')");
            }
        }
    }
?>