<?php
$config = array(
    'CFG' => array(
        'dir' => 'plugins',
    ),

    'PLUGINS' => array(),
    'PLUGINS_LOADED' => array(),
    'PLUGINS_DISABLED' => array(),
    'PLUGINS_ERROR' => array(),
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
                        include($config['CFG']['dir'].'/'.$config['PLUGINS'][$i].'/'.$GPA_PLUGINS['location'].'/'.$item);
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