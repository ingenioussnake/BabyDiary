<?php
    include '../db/dba.php';
    $dba = new DBA();
    $dba->connect();
    echo json_encode(getProfile(1));
    $dba->disconnect();

    function getProfile ($baby_id) {
        global $dba;
        $result = $dba->query("SELECT * FROM baby WHERE id = ". $baby_id . ";");
        return $result[0];
    }
?>