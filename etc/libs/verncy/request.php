<?php
require_once "__autoload.php";
use modules\mybook\user\User;
use configs\Websets;
Websets::startDatabase();
$user = new User();
$user->start_session();
$user_name = "";
$page_definition = array(
    "index.php" => "page/default.php",
    "MyBook"=> "page/default.php",
    "home" => "page/home.php",
    "profile" => "page/profile.php",
    "cpanel" => "page/admin.php"
);
$path = basename($_SERVER['REQUEST_URI']);
if($path == "logout"){
    if(isset($_SESSION['uid']))
        unset($_SESSION['uid']);
    $path = "MyBook";
}else if(($index = stripos($path, "@")) && stripos($_SERVER['REQUEST_URI'], "profile@")){   
    $user_name = substr($path, $index+1);
    $path = "profile";
}
if(array_key_exists($path,$page_definition)){
    if($user->isLogin()){
        if($path == "MyBook"){
            $page = $page_definition["home"]; 
        }else{
            $page = $page_definition[$path];   
        }            
    }else{
        $page = $page_definition["MyBook"];            
    }
}else{
    $page = "page/404.php";   
}
?>