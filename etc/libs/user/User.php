<?php
namespace libs\user;
require_once "setup.php";
use libs\database\Database;
use configs\Vency;
class User{
	private $first_name, $last_name, $user_id, $name, $dob, $email, $is_active, $is_admin, $profile_pic;
	static $db;
    private $chnages;
	public function __construct(){	
        self::$db = new Database();
        $this->changes = array();
	} 	
	public function login($user_id, $password){
		if($result = self::$db->fetch_query_results("select user_id from users where password = md5('".$password."') and user_id = '".$user_id."'")){
            self::$db->query("update users set is_active = True where user_id = '".$user_id."'");
			$this->start_session(self::getUserData("users.user_id = '".$user_id."'"));
			return 1;
		}else{
			return 0;
		}
	}		
	
	public static function getUserData($condition = 1){        
        self::$db = new Database();
		if($result = self::$db->fetch_query_results("select users.user_id, first_name, last_name, dob, email, profile_pic, is_admin, is_active from users join profile on users.user_id = profile.user_id where ".$condition)){
			return $result;
		}else{
			return 0;
		}
	}
	
	public function addUser($user_id, $pass, $first_name, $last_name, $email, $dob){
		if(self::$db->query('call addUser("'.$user_id.'","'.$pass.'","'.$first_name.'","'.$last_name.'","'.$email.'", "'.$dob.'")')){
			$this->start_session(self::getUserData("users.user_id = '".$user_id."'"));
            return 1;
		}else{
			return 0;
		}
	}
    
    public static function getUsers($condition = 1){
        $results = self::getUserData($condition);
        $users_data = array();
        if($results != null){
        foreach($results as $data){
            $user = new User();
            $user->setUserData(false, $data);
            array_push($users_data, $user);
        }
        }
        return $users_data;        
    }
    	
	public function start_session($data = ''){
		if(!empty($data)){
			$_SESSION['uid'] = $data[0]; 	
		}
		if(isset($_SESSION['uid'])){
			$this->user_id = $_SESSION['uid']['user_id'];	//username address for user
			$this->first_name = $_SESSION['uid']['first_name'];	// first name 
			$this->last_name = $_SESSION['uid']['last_name'];	// last name 	
            $this->dob = $_SESSION['uid']['dob'];
            $this->email = $_SESSION['uid']['email'];
            $this->profile_pic = $_SESSION['uid']['profile_pic'];
            $this->status = $_SESSION['uid']['is_active'];
            $this->is_admin = $_SESSION['uid']['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}else{
			return 0;
		}
	}
    
    public function setUserData($is_current = true, $data = ''){
		if(!empty($data) && $is_current){
			$_SESSION['uid'] = $data[0]; 	
		}
		if(isset($_SESSION['uid']) && $is_current){
			$this->user_id = $_SESSION['uid']['user_id'];	//username address for user
			$this->first_name = $_SESSION['uid']['first_name'];	// first name 
			$this->last_name = $_SESSION['uid']['last_name'];	// last name 	
            $this->dob = $_SESSION['uid']['dob'];
            $this->email = $_SESSION['uid']['email'];
            $this->profile_pic = $_SESSION['uid']['profile_pic'];
            $this->status = $_SESSION['uid']['is_active'];
            $this->is_admin = $_SESSION['uid']['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}else{
			$this->user_id = $data['user_id'];	//username address for user
			$this->first_name = $data['first_name'];	// first name 
			$this->last_name = $data['last_name'];	// last name 	
            $this->dob = $data['dob'];
            $this->email = $data['email'];
            $this->profile_pic = $data['profile_pic'];
            $this->status = $data['is_active'];
            $this->is_admin = $data['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}
	}
    
    public function setUserInfo($attribute, $value){
        $this->$attribute = $value;
        array_push($this->changes, $attribute);
    }
    
    public function commitChnages(){
        $sql = "update profile set ";
        $first = true;/*
        echo "<br/><br/>";
        print_r($this->changes);
        echo "<br/><br/>";*/
        foreach($this->changes as $changes){
            $_SESSION['uid'][$changes] = $this->$changes;
            $sql .= (($first)? "":", ").$changes." = '".$this->$changes."' ";
            $first = false;
        }
        $sql .= " where user_id = '".$this->user_id."'";
        if(self::$db->query($sql)){
            return 1;   
        }else{
            return 0;
        }        
    }
    
    public static function getFriends($condition = 1){
         self::$db = new Database();
		if($result = self::$db->fetch_query_results("select friend_id, first_name, last_name from friend_of as fof join profile as p on fof.friend_id = p.user_id where ".$condition)){
			return $result;
		}else{
			return 0;
		}
    }
	
	public function getUserBy($attribute){
		return (!empty($this->$attribute))? $this->$attribute:0;
	}
	
	public function isUserLogin($user_id){
		return ($this->user_id ===  $user_id)? 1:0;	
	}
    
    public function is_active(){
        return $this->is_active;   
    }
    
    public function is_admin(){
        return $this->is_admin;   
    }
	
	public function isLogin($username = ''){
		return (isset($_SESSION['uid']['user_id']) && ($_SESSION['uid']['user_id'] ==  $this->user_id))? 1:0;	
	}
	
	public function logOut(){
        if(self::$db->query("update users set is_active = False where user_id =  '".$user_id."'")){
		     unset($_SESSION['uid']);
			 return 1;
		}else{
			return 0;
		}
	}
	
	public function removeUser($user_id){
		return ($this->isLogin($user_id))? (self::$db->query('delete from users where user_id = "'.$user_id.'"'))? 1:0:0;
	}	
	
	public function userExists($user_id){
		return self::$db->recordExist('select count(*) > 0 from users where user_id = "'.$user_id.'"');
	}
}


?>