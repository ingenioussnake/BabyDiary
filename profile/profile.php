<?php
    include '../db/dba.php';
    $dba = new DBA();
    $dba->connect();
    if (isset($_GET["type"])) {
        $type = $_GET["type"];
        if ($type == "info") {
            echo json_encode(getProfile(1));
        } else if ($type == "basic") {
            echo json_encode(getBasicInfo(1));
        } else if ($type == "avatar") {
            echo getAvatar(1);
        }
    }
    $dba->disconnect();

    function getProfile ($baby_id) {
        global $dba;
        $result = $dba->query("SELECT name, birthday, sex, blood, height, weight FROM baby WHERE id = ". $baby_id . ";");
        return $result[0];
    }

    function getBasicInfo ($baby_id) {
        global $dba;
        $result = $dba->query("SELECT name, birthday FROM baby WHERE id = ". $baby_id . ";");
        return $result[0];
    }

    function getAvatar ($baby_id) {
        global $dba;
        $result = $dba->query("SELECT avatar from baby WHERE id = ". $baby_id . ";", function($row){
            return $row["avatar"];
        });
        return $result[0];
    }
?>