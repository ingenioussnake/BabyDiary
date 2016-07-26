<?php
    if (!isset($_GET["type"])) {
        echo "wrong get";
        exit;
    }
    include '../db/dba.php';
    $type = $_GET["type"];
    $dba = new DBA();
    $dba->connect();
    switch ($type) {
        case 'date':
            echo getDates();
            break;

        case 'list':
            echo getList($_GET["date"]);
            break;

        case 'picture':
            echo getPictures($_GET["id"]);
            break;
    }
    $dba->disconnect();

    function getDates () {
        global $dba;
        $result = $dba->query("select date from dining UNION select date from sleep UNION select date from shit UNION select date from memo ORDER BY date DESC;", function ($row) { return $row["date"];});
        return json_encode($result);
    }

    function getList ($date) {
        $types = array("dining", "sleep", "shit", "height", "weight", "memo");
        $results = array();
        global $dba;
        foreach ($types as $type) {
            $result = $dba->query("select * from " . $type . " WHERE baby = 1 AND date = '" . $date. "';", function($row) use ($type) {
                $row["type"] = $type;
                return $row;
            });
            $results = array_merge($results, $result);
        }
        header("charset=utf-8");
        return json_encode($results);
    }

    function getPictures ($id) {
        global $dba;
        $result = $dba->query("select picture from picture WHERE memo = '" . $id. "';", function($row) {
            echo json_encode($row);
            return $row["picture"];
        });
        if (count($result) > 0) {
            header("Content-Type:image/*");
            return $result[0];
        }
    }
?>