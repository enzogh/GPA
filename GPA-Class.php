<?php
$config = array(
    'CFG' => array(
        'dir' => 'plugins', // Plugins Directory
        'dir_condenser' => 'condenser', // Condenser Directory
    ),

    'PLUGINS' => array(),
    'PLUGINS_LOADED' => array(),
    'PLUGINS_DISABLED' => array(),
    'PLUGINS_ERROR' => array(),

    'PLUGINS_CACHE' => array(),
);

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

                        $handle = fopen($file, 'r');
                        $contents = fread($handle, filesize($file));
                        fclose($handle);

                        if($GPA_PLUGINS['condenser'] == 'enabled'){
                            array_push($config['PLUGINS_CACHE'], base64_encode($contents));
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
                fwrite($handle, "<?php\n// Plugins Condenser : ".$i."\n".$code."\n");
            } else {
                fwrite($handle, "\n// Plugins Condenser : ".$i."\n".$code."\n");
            }
        }

        fclose($handle);
    }
}