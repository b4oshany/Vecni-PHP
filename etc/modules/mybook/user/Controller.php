<?php
namespace modules\mybook\user;
require_once "setup.php";
use modules\mybook\user\User;
use configs\Websets;
Websets::startDatabase();
class Controller{
    public static function register(){
        if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['username']) && $_POST['pass']){
            $uname = $_POST['username'];
            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $email = $_POST['email'];
            $dob = $_POST['dob'];
            $pass = $_POST['pass'];
            $user = new User();
            echo $user->addUser($uname, $pass, $fname, $lname, $email, $dob);               
        }
    }

     // Attempted to login users
    public static function login(){
        if(!empty($_POST['username']) && !empty($_POST['pass'])){
            $username = $_POST['username'];
            $pass = $_POST['pass']; 
            $user = new User();
            echo $user->login($username, $pass);    
        }       
    }
}
?>