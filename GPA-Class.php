<?php
$config = array(
    'CFG' => array(
        'dir'               => 'plugins', // Plugins Directory
        'dir_condenser'     => 'condenser', // Condenser Directory
        'dir_secure_load'   => 'secure', // Secure Load Result Directory
        'dir_encrypt_file'  => 'encrypt', // Encrypt File Result Directory

        'display_error' => 'disabled', // (enabled or disabled)
        'secure_load'   => 'disabled', // (enabled or disabled)
        'encrypt_file'  => 'dsiabled', // (enabled or disabled)
    ),

    'PLUGINS' => array(),
    'PLUGINS_LOADED' => array(),
    'PLUGINS_DISABLED' => array(),
    'PLUGINS_ERROR' => array(),

    'PLUGINS_CACHE' => array(),

    'PLUGINS_SECURE_LOAD' => array(),
    'PLUGINS_SECURE_LOAD_RESULT' => array(),

    'PLUGINS_ENCRYPT_FILE' => array(),
    'PLUGINS_ENCRYPT_FILE_RESULT' => array(),

    'BLACKLIST' => array(
        'exec',
        'passthru',
        'system',
        'shell_exec',
        'popen',
        'proc_open',
        'pcntl_exec',
        'eval',
    ),
);

/**
 * GPA - GarryHost Plugins Autoloader
 */

if($config['CFG']['display_error'] == 'disabled'){
    error_reporting(0);
}

$GPA = scandir($config['CFG']['dir'], 1);

foreach($GPA as $dir){
    if($dir != '..' && $dir != '.'){
        array_push($config['PLUGINS'], $dir);
    }
}

for($i = 0; $i < count($config['PLUGINS']); $i++){
    if(file_exists($config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/GPA-autoloader.php')){
        include($config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/GPA-autoloader.php');

        if($GPA_PLUGINS['status'] == 'enabled'){
            if($GPA_PLUGINS['role'] == 'classes'){
                $scandir = scandir($config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/'.$GPA_PLUGINS['location'], 1);

                foreach($scandir as $item){
                    if($item != '.' && $item != '..'){
                        $file = $config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/'.$GPA_PLUGINS['location'].$item;

                        include($file);

                        if($GPA_PLUGINS['condenser'] == 'enabled'){
                            $handle = fopen($file, 'r');
                            $contents = fread($handle, filesize($file));
                            fclose($handle);

                            array_push($config['PLUGINS_CACHE'], base64_encode($contents));
                        }

                        if($config['CFG']['secure_load'] == 'enabled'){
                            if(!file_exists($config['CFG']['dir_secure_load'].'/GPA-Secure-Result.txt')){
                                $handle = fopen($file, 'r');
                                $contents = fread($handle, filesize($file));
                                fclose($handle);

                                array_push($config['PLUGINS_SECURE_LOAD'], base64_encode($contents));
                            }
                        }

                        if($config['CFG']['encrypt_file'] == 'enabled'){
                            if(!file_exists($config['CFG']['dir_encrypt_file'].'/GPA-Encrypt.gpa')){
                                $handle = fopen($file, 'r');
                                $contents = fread($handle, filesize($file));
                                fclose($handle);

                                array_push($config['PLUGINS_ENCRYPT_FILE'], base64_encode($contents));
                            }
                        }
                    }
                }

                array_push($config['PLUGINS_LOADED'], array('file_directory' => $config['PLUGINS'][$i], 'plugins_name' => $GPA_PLUGINS['name'], 'plugins_role' => $GPA_PLUGINS['role'], 'plugins_location' => $GPA_PLUGINS['location'], 'plugins_version' => $GPA_PLUGINS['version'], 'plugins_enabled' => $GPA_PLUGINS['status']));
            } elseif($GPA_PLUGINS['role'] == 'execute') {
                if(!empty($GPA_PLUGINS['file_execute'])){
                    include($config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/'.$GPA_PLUGINS['location'].$GPA_PLUGINS['file_execute']);

                    array_push($config['PLUGINS_LOADED'], array('file_directory' => $config['PLUGINS'][$i], 'plugins_name' => $GPA_PLUGINS['name'], 'plugins_role' => $GPA_PLUGINS['role'], 'plugins_location' => $GPA_PLUGINS['location'], 'plugins_version' => $GPA_PLUGINS['version'], 'plugins_enabled' => $GPA_PLUGINS['status']));
                } else {
                    array_push($config['PLUGINS_ERROR'], array('file_directory' => $config['PLUGINS'][$i], 'message' => 'Variable executer not found'));
                }
            }
        } elseif($GPA_PLUGINS['status'] == 'disabled' || $GPA_PLUGINS['status'] != 'enabled') {
            array_push($config['PLUGINS_DISABLED'], array('file_directory' => $config['PLUGINS'][$i], 'plugins_name' => $GPA_PLUGINS['name'], 'plugins_role' => $GPA_PLUGINS['role'], 'plugins_location' => $GPA_PLUGINS['location'], 'plugins_version' => $GPA_PLUGINS['version'], 'plugins_enabled' => $GPA_PLUGINS['status']));
        }
    } else {
        array_push($config['PLUGINS_ERROR'], array('file_directory' => $config['PLUGINS'][$i], 'message' => 'GPA not found'));
    }
}

if(!empty($config['PLUGINS_CACHE'])){
    if(!file_exists($config['CFG']['dir_condenser'].'/GPA-condenser.php')){
        $handle = fopen($config['CFG']['dir_condenser'].'/GPA-condenser.php', 'a+');

        for($i = 0; $i < count($config['PLUGINS_CACHE']); $i++){
            $code = base64_decode($config['PLUGINS_CACHE'][$i]);
            $code = str_replace('<?php', '', $code);
            $code = str_replace('?>', '', $code);

            if($i == 0){
                fwrite($handle, "<?php\n// Plugins Condenser : ".$i.$code."\n");
            } else {
                fwrite($handle, "\n// Plugins Condenser : ".$i.$code."\n");
            }
        }

        fclose($handle);
    }
}

if($config['CFG']['secure_load'] == 'enabled'){
    if(!file_exists($config['CFG']['dir_secure_load'].'/GPA-Secure-Result.txt')){
        if(!empty($config['PLUGINS_SECURE_LOAD'])){
            for($i = 0; $i < count($config['PLUGINS_SECURE_LOAD']); $i++){
                $code = strtolower(base64_decode($config['PLUGINS_SECURE_LOAD'][$i]));

                foreach($config['BLACKLIST'] as $blacklist){
                    if(strpos($code, $blacklist) !== FALSE){
                        array_push($config['PLUGINS_SECURE_LOAD_RESULT'], 'Suspect Found : '.$blacklist);
                    }
                }
            }

            $handle = fopen($config['CFG']['dir_secure_load'].'/GPA-Secure-Result.txt', 'a+');

            if(empty($config['PLUGINS_SECURE_LOAD_RESULT'])){
                fwrite($handle, "/**\n *\n * GPA Secure Load\n *\n **/\n\nNothing is suspicious");
            } else {
                fwrite($handle, "/**\n *\n * GPA Secure Load\n *\n **/\n\nsuspicious function used found :\n");
                foreach($config['PLUGINS_SECURE_LOAD_RESULT'] as $found){
                    fwrite($handle, $found."\n");
                }
            }

            fclose($handle);
        }
    }
}

if($config['CFG']['encrypt_file'] == 'enabled'){
    function decode($str){
        return pack('H*', $str);
    }

    function encode($str){
        return array_shift(unpack('H*', $str));
    }

    if(!file_exists($config['CFG']['dir_encrypt_file'].'/GPA-Encrypt.gpa')){
        if(!empty($config['PLUGINS_ENCRYPT_FILE'])){
            for($i = 0; $i < count($config['PLUGINS_ENCRYPT_FILE']); $i++){
                $code = base64_decode($config['PLUGINS_ENCRYPT_FILE'][$i]);
                $code = str_replace('<?php', '', $code);
                $code = str_replace('?>', '', $code);

                array_push($config['PLUGINS_ENCRYPT_FILE_RESULT'], encode($code));
            }

            $handle = fopen($config['CFG']['dir_encrypt_file'].'/GPA-Encrypt.gpa', 'a+');

            for($i = 0; $i < count($config['PLUGINS_ENCRYPT_FILE_RESULT']); $i++){
                fwrite($handle, "<GPA><GPA>".$config['PLUGINS_ENCRYPT_FILE_RESULT'][$i]);
            }

            fclose($handle);
        }
    } else {
        $handle = fopen($config['CFG']['dir_encrypt_file'].'/GPA-Encrypt.gpa', 'r');
        $contents = fread($handle, filesize($config['CFG']['dir_encrypt_file'].'/GPA-Encrypt.gpa'));
        fclose($handle);

        $contents = explode('<GPA><GPA>', $contents);

        if(!file_exists($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php')){
            $handle = fopen($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php', 'a+');
            fwrite($handle, "<?php");

            foreach($contents as $code){
                if(!empty($code)){
                    fwrite($handle, "\n".decode($code));
                }
            }

            fclose($handle);

            include($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php');
            unlink($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php');
        } else {
            include($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php');
            unlink($config['CFG']['dir_encrypt_file'].'/GPA-Temp.php');
        }
    }
}