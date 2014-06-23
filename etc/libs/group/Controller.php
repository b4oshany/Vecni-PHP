<?php
namespace modules\mybook\group;
require_once "setup.php";
use modules\mybook\group\Group;
use modules\mybook\user\User;
use configs\Websets;
Websets::startDatabase();
class Controller{    
    public static function addGroup(){
        $user = new User();
        $user->start_session();
        if(!empty($_POST['groupid']) && !empty($_POST['groupname'])){
            return Group::addGroup($_POST['groupid'], $_POST['groupname'], $user->getUserBy("user_id"));
        }
        return 0;
    }
    
    public static function getGroups(){
        $html = '';
        $groups = Group::getGroups(); 
        if($groups != null){
            foreach($groups as $group){
                $html .=  '
                <tr>
                    <td><a href="group@'.$group->getGroupBy("group_id").'">'.$group->getGroupBy("group_id").'</a></td>
                    <td>'.$group->getGroupBy("group_name").'</td>
                    <td><a href="profile@'.$group->getGroupBy("group_owner_id").'">'.$group->getGroupBy("group_owner_name").'</a></td>
                    <td>'.$group->getGroupBy("date_created").'</td>
                </tr>';
            }
        return $html;
        }
        return 0;
    }
}
?>