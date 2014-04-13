<?php
    require_once '__autoload.php';
    if(!empty($_POST['rt'])){
        switch $_POST['rt']{
         case 'login':
            use modules\mybook\user\Controller;
            Controller::login();   
            break;
        case 'uregister':
            use modules\mybook\user\Controller;
            Controller::register();
            break;
        }        
    }
?>