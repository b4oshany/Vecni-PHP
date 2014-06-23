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
    
    public static function update(){
        $user = new User();
        $user->start_session();
        if(!empty($_POST['first_name']))
           $user->setUserInfo("first_name", $_POST["first_name"]);
        if(!empty($_POST['last_name']))
            $user->setUserInfo("last_name", $_POST["last_name"]);
        if(!empty($_POST['email']))
            $user->setUserInfo("email", $_POST["email"]);
        if(!empty($_POST['dob']))
            $user->setUserInfo("dob", $_POST["dob"]);
        if(!empty($_POST['profile_pic']))
            $user->setUserInfo("profile_pic", $_POST["profile_pic"]); 
        return $user->commitChnages();        
    }
}
?>