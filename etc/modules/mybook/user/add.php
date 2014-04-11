<?php
require_once "setup.php";
use modules\mybook\user\User;
if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['username']) && $_POST['pass']){
    $uname = $_POST['username'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $pass = $_POST['pass'];
    $user = new User();
    echo $user->addUser($fname, $lname, $uname, $pass);               
}

?>