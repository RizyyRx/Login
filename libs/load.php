<?
include_once 'libs/Classes/Database.class.php';
include_once 'libs/Classes/Session.class.php';
include_once 'libs/Classes/User.class.php';

session::start();


global $__site_config;
$__site_config_path="/home/rizwankendo/login_config.json";
$__site_config=file_get_contents($__site_config_path);

function get_config($key){
    global $__site_config;
    $array=json_decode($__site_config,true);//contents returned as associative array to $array
    if(isset($array[$key])){
        return $array[$key];
    }
    else{
        return NULL;
    }
}

?>