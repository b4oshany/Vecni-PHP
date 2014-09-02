<?php
namespace libs\user;
require_once "setup.php";
require_once "User.php";
use configs\Vecni;
use libs\vecni\Object;

class UserRole extends Object{    
    public function user_add($group_name, $user){
        array_push($this->$group_name = $user);
    }
    
    public function is($group_name, $user){
        return in_array($this->$group_name, $user);
    }
    
    public function create_group($group_name){
        $this->$group_name = array();
    }
}
?>