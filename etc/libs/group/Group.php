<?php
namespace modules\mybook\group;
require_once "setup.php";
use libs\database\Database;
use configs\Websets;
class Group{
    private $group_id, $group_owner, $group_owner_id, $group_name, $date_created, $group_editors;
    static $db;
	public function __construct(){	
        self::$db = new Database();
	} 	
    
    
    public static function addGroup($group_id, $group_name, $group_owner){
        self::$db = new Database();
        if(self::$db->query("insert into groups(group_id, group_name) values('".$group_id."', '".$group_name."');
    insert into group_creator(group_id, owner_id) values('".$group_id."', '".$group_owner."')")){
            return true;
        }
        return false;
    }
    
    public static function getGroupMembers($condition = 1){
        self::$db = new Database();
        if($results = self::$db->fetch_query_results("select g.group_id, g.group_name, adm.user_id, first_name, last_name from groups as g join add_member as adm on g.group_id = adm.group_id join profile as pro on pro.user_id = adm.user_id where ".$condition)){
            return $results;
        }else{
            return false;   
        }
    }
        
    public static function getGroups($condition = 1){
          self::$db = new Database();
        if($results = self::$db->fetch_query_results("select g.group_id, g.group_name, gc.date_created, gc.owner_id, first_name, last_name from groups as g join group_creator as gc on g.group_id = gc.group_id join profile as pro on pro.user_id = gc.owner_id where ".$condition)){
         $groups = array();
            foreach($results as $data){
                $group = new Group();
                $group->setGroupData($data);
                array_push($groups, $group);
            }
            return $groups;
        }else{
            return false;   
        }          
    }
    
     public function setGroupData($data){
        $this->group_id = $data["group_id"];
        $this->group_name = $data["group_name"];
        $this->group_owner_name = $data["first_name"]." ".$data["last_name"];
        $this->group_owner_id = $data["owner_id"];
        $this->date_created = $data["date_created"];
    }
    
    public function getGroupBy($attribute){
		return (!empty($this->$attribute))? $this->$attribute:0;
	}
    
    
}

?>