<?php
require_once '__autoload.php';
use modules\mybook\user\Controller as USER_CONTROLLER;
use modules\mybook\post\Controller as POST_CONTROLLER;
use modules\mybook\group\Controller as GROUP_CONTROLLER;
use modules\mybook\group\Group;
use modules\mybook\post\Post;
use configs\Websets;
Websets::startDatabase();
if(!empty($_POST['rt'])){        
    switch($_POST['rt']){
     case 'usignin':
        echo USER_CONTROLLER::login();   
        break;
    case 'usignup':
        echo USER_CONTROLLER::register();
        break;
    case "uupdate":
        echo USER_CONTROLLER::update();
        break;
    case "apost":
        echo POST_CONTROLLER::getPosts(); 
        break;
    case "ggroup":
        echo GROUP_CONTROLLER::getGroups();
        break;
    case "addgroup":
        $status = GROUP_CONTROLLER::addGroup();
        if($status){
            try{
                $groups = Group::getGroups("g.group_id = '".$_POST['groupid']."'");
                $_SESSION["bundle"] = $groups[0];                
                echo 1;
            }catch(Exception $e){
                echo 0;
            }
        }else{
            echo $status;
        }
        break;
    case "postex":
        if(!empty($_POST['title']) && !empty($_POST['content'])){
            if(Post::addPost($_SESSION['uid']['user_id'], "text", $_POST['title'], $_POST['content'], '')){                
                echo POST_CONTROLLER::getPosts();
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
        break;
    default:
        echo 0;
    }        
}
?>