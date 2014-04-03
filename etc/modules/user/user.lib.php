<?php
$settings_file = 'config/server.settings.php';
$database_file = 'config/database.lib.php';
require_once  (is_file('etc/'.$settings_file))? 'etc/'.$settings_file: '../../'.$settings_file;
require_once (is_file('etc/'.$database_file))? 'etc/'.$database_file:'../../'.$database_file;

class User{
	protected $first_name, $last_name, $username, $name;
	protected $db;
	
	public function __construct(){	
		$this->db = new Db_Functions();	
	} 	
	
	public function login($username, $password){
		if($result = $this->db->fetch_query_results("select id from user where password = md5('".$password."') and username = '".$username."'")){
			$this->start_session($this->getUserData($username));
			return 1;
		}else{
			return 0;
		}
	}		
	
	public function getUserData($username){
		if($result = $this->db->fetch_query_results("select id, first_name, last_name, username from user where username = '".$username."'")){
			return $result;
		}else{
			return 0;
		}
	}
	
	public function addUser($first_name, $last_name, $username, $pass){
		if($this->db->query('INSERT INTO user(first_name, last_name, username, password) VALUES ("'.$first_name.'","'.$last_name.'","'.$username.'", md5("'.$pass.'"))')){
			return $this->login($username, $pass);
		}else{
			return 0;
		}
	}
	
	public function start_session($data = ''){
		if(!empty($data)){
			$_SESSION['uid'] = $data[0]; 	
		}
		if(isset($_SESSION['uid'])){
			$this->username = $_SESSION['uid']['id'];	//user id
			$this->username = $_SESSION['uid']['username'];	//username address for user
			$this->first_name = $_SESSION['uid']['first_name'];	// first name 
			$this->last_name = $_SESSION['uid']['last_name'];	// last name 	
			$this->name = $this->first_name.' '.$this->last_name;
		}else{
			return 0;
		}
	}
	
	public function getUserBy($attribute){
		return (!empty($this->$attribute))? $this->$attribute:0;
	}
	
	public function isUserLogin($username){
		return ($this->username ===  $username)? 1:0;	
	}
	
	public function isLogin($username = ''){
		return (isset($_SESSION['uid']['username']) && ($_SESSION['uid']['username'] ==  $this->username))? 1:0;	
	}
	
	public function logOut(){
		unset($_SESSION['uid']);
	}
	
	public function removeUser($username){
		return ($this->isLogin($username))? ($this->db->query('delete from user where username = "'.$username.'"'))? 1:0:0;
	}	
	
	public function userExists($username){
		return $this->db->recordExist('select count(*) > 0 from user where username = "'.$username.'"');
	}
}


?>