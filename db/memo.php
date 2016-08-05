<?php
    include './dba.php';
    include '../config.php';
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
    define("MAX_PIC_SIZE", 3000000);
    $root = get_config("../php.ini")["picture_location"];
    $dir = prepareDestination($root, 1, date("Y-m-d"));
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

        case 'upload':
            echo uploadPicture();
            break;

        case 'removePic':
            echo removePicture($_POST["path"]);
            break;

        case 'list':
            echo getMemoList($_GET["offset"], $_GET["size"]);
            break;

        case 'avatar':
            echo getBabyAvatar($_GET["baby"]);
            break;

        case 'pic_count':
            echo getPictureIds($_GET["id"]);
            break;

        case 'picture':
            echo getPicture($_GET["id"]);
            break;
    }
    $dba->disconnect();

    function insertMemo () {
        global $dba;
        $id = _insertMemo();
        if (isset($_POST["pictures"])) {
            $pictures = $_POST["pictures"];
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

    function insertPictures ($memo_id, $pictures) {
        global $dba;
        $length = count($pictures);
        for ($i = 0; $i < $length; $i++) {
            $dba->exec("INSERT INTO picture VALUES (NULL, ". $memo_id . ", '" . $pictures[$i] . "');");
        }
    }

    function uploadPicture () {
        global $dba, $imageType, $root, $dir;
        $picture = $_FILES["picture"];
        $file = $picture["tmp_name"];
        if (is_uploaded_file($file) && $picture["size"] < MAX_PIC_SIZE && in_array($picture["type"], $imageType)) {
            $path = $dir.time()."_".$picture["size"].".".pathinfo($picture["name"])["extension"];
            echo move_uploaded_file($file, $root.$path) ? $path : "move failed!";
        } else {
            echo false;
        }
    }

    function removePicture ($path) {
        global $dba, $root;
        $file = $root.$path;
        if (is_file($file)) {
            unlink($file);
        }
        return $dba->exec("DELETE FROM picture WHERE location = '". $path . "';");
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

    function getMemoList ($offset, $size) {
        global $dba;
        $result = $dba->query("SELECT * FROM memo ORDER BY date DESC, time DESC LIMIT " . $offset . ", " . $size . ";");
        return json_encode($result);
    }

    // function getMemoList ($offset, $size) {
    //     global $dba;
    //     $result = $dba->query("SELECT * FROM memo ORDER BY date DESC, time DESC LIMIT " . $offset . ", " . $size . ";", function($row){
    //         global $dba;
    //         $pics = $dba->query("SELECT id FROM picture WHERE memo = ". $row["id"] .";", function($pic){
    //             return $pic["id"];
    //         });
    //         $row["pictures"] = $pics;
    //     });
    //     return json_encode($result);
    // }

    function getBabyAvatar ($baby_id) {
        global $dba;
        $result = $dba->query("SELECT avatar from baby WHERE id = ". $baby_id . ";", function($row){
            return $row["avatar"];
        });
        return $result[0];
    }

    function getPictureIds ($memo_id) {
        global $dba;
        $result = $dba->query("SELECT id from picture WHERE memo = ". $memo_id . ";", function($row){
            return $row["id"];
        });
        return json_encode($result);
    }

    function getPicture ($pic_id) {
        global $dba, $root;
        $result = $dba->query("SELECT location from picture WHERE id = ". $pic_id . ";", function($row){
            return $row["location"];
        });
        header("Content-Type:image/*");
        $file = $result[0];
        return file_get_contents($root.$file);
    }

    function prepareDestination ($root, $baby_id, $date) {
        if(!file_exists($root)) {
            mkdir($root);
        }
        $dir = $baby_id."/";
        if(!file_exists($root.$dir)) {
            mkdir($root.$dir);
        }
        $dir .= $date."/";
        if(!file_exists($root.$dir)) {
            mkdir($root.$dir);
        }
        if (!is_writable($root.$dir)) {
            echo "Not writable";
            exit;
        }
        return $dir;
    }
?>