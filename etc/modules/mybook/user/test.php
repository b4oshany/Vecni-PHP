<?php
require_once "setup.php";
use modules\mybook\user\User;
$user = new User();
echo $user->login("ok", "sdad");    
?>