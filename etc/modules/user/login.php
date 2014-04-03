<?php
require_once 'user.lib.php';
// Attempted to login users
if(!empty($_POST['username']) && !empty($_POST['pass'])){
	$username = $_POST['username'];
	$pass = $_POST['pass']; 
    $user = new User();
    echo $user->login($username, $pass);    
}
?>