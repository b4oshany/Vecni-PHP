<?php
namespace modules\mybook\user;
require_once "setup.php";
use libs\database\Database;
use configs\Websets;
class User{
	protected $first_name, $last_name, $user_id, $name, $dob, $email, $is_active, $is_admin, $profile_pic;
	protected $db;
	
	public function __construct(){	
		$this->db = new Database();	
	} 	
	
	public function login($user_id, $password){
		if($result = $this->db->fetch_query_results("select user_id from users where password = md5('".$password."') and user_id = '".$user_id."'")){
            $this->db->query("update users set is_active = True where user_id = '".$user_id."'");
			$this->start_session($this->getUserData("users.user_id = '".$user_id."'"));
			return 1;
		}else{
			return 0;
		}
	}		
	
	public function getUserData($condition = 1){
		if($result = $this->db->fetch_query_results("select users.user_id, first_name, last_name, dob, email, profile_pic, is_admin, is_active from users join profile on users.user_id = profile.user_id where ".$condition)){
			return $result;
		}else{
			return 0;
		}
	}
	
	public function addUser($user_id, $pass, $first_name, $last_name, $email, $dob){
		if($this->db->query('call addUser("'.$user_id.'","'.$pass.'","'.$first_name.'","'.$last_name.'","'.$email.'", "'.$dob.'")')){
			$this->start_session($this->getUserData("users.user_id = '".$user_id."'"));
            return 1;
		}else{
			return 0;
		}
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
		return (isset($_SESSION['uid']['username']) && ($_SESSION['uid']['username'] ==  $this->username))? 1:0;	
	}
	
	public function logOut(){
        if($this->db->query("update users set is_active = False where user_id =  '".$user_id."'")){
		     unset($_SESSION['uid']);
			 return 1;
		}else{
			return 0;
		}
	}
	
	public function removeUser($user_id){
		return ($this->isLogin($user_id))? ($this->db->query('delete from users where user_id = "'.$user_id.'"'))? 1:0:0;
	}	
	
	public function userExists($user_id){
		return $this->db->recordExist('select count(*) > 0 from users where user_id = "'.$user_id.'"');
	}
}


?>