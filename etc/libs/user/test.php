<?php
require_once "setup.php";
use modules\mybook\user\User;
use configs\Websets;
Websets::startDatabase();
$user = new User();
$user->start_session();
//echo $user->addUser("b4woads", "oshany1991", "Oshane", "Bailey", "b4.oshany@gmail.com", "1991/09/30");    
$user->setUserInfo("first_name", "Olando");
echo $user->commitChnages();
?>