<?php
    $db = new mysqli('localhost', 'root', '', 'bookshop');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    function verbose ($ok=1, $info="") {
        if ($ok==0) { http_response_code(400); }
        exit(json_encode(["ok"=>$ok, "info"=>$info]));
    }

    if (empty($_FILES) || $_FILES["file"]["error"]) {
        verbose(0, "Failed to move uploaded file.");
    }

    $base_upload_path = '/';
    
    $filePath_root =  __DIR__ . DIRECTORY_SEPARATOR;
    $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
    $ext = strtolower(strrchr($fileName, '.'));
    $filePath = $filePath_root . DIRECTORY_SEPARATOR . $fileName;
    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
    $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
    if ($out) {
        $in = @fopen($_FILES["file"]["tmp_name"], "rb");
        if ($in) { 
            while ($buff = fread($in, 4096)) { 
                fwrite($out, $buff); 
            } 
        } else { 
            verbose(0, "Failed to open input stream"); 
        }
        @fclose($in);
        @fclose($out);
        @unlink($_FILES["file"]["tmp_name"]);
    } else { 
        verbose(0, "Failed to open output stream"); 
    }
    if (!$chunks || $chunk == $chunks - 1) { 
        $nfileName = base_convert(str_replace(' ', '', microtime()) . rand(), 10, 36) . $ext;
        rename("{$filePath}.part", $filePath_root . DIRECTORY_SEPARATOR .$nfileName); 
        $nfile_path = $base_upload_path.$nfileName;
    }
    die(json_encode(array('OK' => 1, 'nfile_path' => $nfile_path, 'name'=>$nfileName)));
?>  