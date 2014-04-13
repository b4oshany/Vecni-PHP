<?php
$page_definition = array(
    "index.php" => "index.php",
    "home" => "page/home.php",
    "profile" => "page/profile.php"
);
$path = basename($_SERVER['REQUEST_URI']);
if(array_key_exists($path,$page_definition)){
    $page = $path;    
}else{
    $page = "page/404.php";   
}
?>