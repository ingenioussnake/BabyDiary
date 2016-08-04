<?php
    include './dba.php';
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
        $folder = "./storage";
        if(!file_exists($folder))
        {
            mkdir($folder);
        }
        for ($i = 0; $i < $length; $i++) {
            if (is_uploaded_file($tmp[$i]) && $size[$i] < MAX_PIC_SIZE && in_array($type[$i], $imageType)) {
                $destination = $folder."/".time().".".pathinfo($names[$i])["extension"];
                if (!move_uploaded_file($tmp[$i], $destination)) {
                    echo "move failed";
                }
                echo json_encode(pathinfo($destination));
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

    function getMemoList ($offset, $size) {
        global $dba;
        $result = $dba->query("SELECT * FROM memo ORDER BY date DESC, time DESC LIMIT " . $offset . ", " . $size . ";");
        return json_encode($result);
    }

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
        global $dba;
        $result = $dba->query("SELECT picture from picture WHERE id = ". $pic_id . ";", function($row){
            return $row["picture"];
        });
        header("Content-Type:image/*");
        return $result[0];
    }

    ##################################################################################################
/*
功        能：读取 ini 文件. [ 不 ] 支持节
版        本：1.1
作        者：Jinsen
日        期：2010-09-20
入        参：ini 文件名:字符串
返    回    值：一个一维:关联数组
修        改：2011-05-31: 配置值中 利用正则表达式替换 多个空格为单一的空格
注        意：ini 文件
            !!! 所有的配置节或配置项 均为小写!!!
            支持 "#" 或 ";" 开头的整行注释;
            支持 "//" 或 "--" 之后的行尾注释;
            多个相同的配置,后面的配置覆盖前面定义的配置;
            
*/
##################################################################################################

function get_config($configfilename)
{
    $debug=0;
    
    #创建空的配置栈
    $configs=array();
    
    $rows=@file($configfilename); #逐行读取记录
    foreach($rows as $row)
    {
        #清理空白字符
        $config=trim($row);
        #过滤掉空行；处理为空行
        if ($config)
        {
            #过滤注释行；处理非注释行
            if(substr($config,0,1)<>"#")
            {
                #删除行尾注释
                if ($pos==strpos($config,"#",0)) {$config=substr($config,0,$pos);}
                #根据“=”分割配置项和配置值，并进行格式化处理
                if ($pos==strpos($config,"=",0))
                {
                    #获取key：配置项
                    $key=strtolower(trim(substr($config,0,$pos)));
                    #获取value：配置值
                    $value=trim(preg_replace('/[ ]{1,}/'," ",substr($config,$pos+1)));
                    #将配置入栈，等待返回
                    $configs["$key"]=$value;
                    #debug模式，打印调式信息
                    if ($debug) {print "\t$key:$value\n";}
                }                            
            }
        }
    }
    
    return $configs;
}
?>