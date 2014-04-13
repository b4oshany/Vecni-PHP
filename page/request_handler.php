<?php
    require_once '__autoload.php';
    use modules\mybook\user\Controller;
    if(!empty($_POST['rt'])){        
        switch($_POST['rt']){
         case 'usignin':
            echo Controller::login();   
            break;
        case 'usignup':
            echo Controller::register();
            break;
        }        
    }
?>