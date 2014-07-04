<?php
namespace libs\user;
require_once "setup.php";
use configs\Vecni;
class User{
	public $first_name, $last_name, $user_name, $name, $dob, $email, $is_active, $is_admin, $profile_pic;
    private $chnages;
	public function __construct(){	
        $this->changes = array();
	} 	
	public function login($user_name, $password){
        $stmt = Vecni::$db->prepare("call login('$user_name','$password')");
		if($stmt->execute()){
			$this->start_session($stmt->fetch());
            if(isset($_SESSION['uid'])){
                return 1;
            }else{
                return 0;
            }
		}else{
			return 0;
		}
	}		
	
	public static function getUserData($condition = 1){   
        $stmt = Vecni::$db->query("select users.user_name, first_name, last_name, contact_number, email, profile_pic, is_admin, is_active from users where ".$condition);
        $user = new User;
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $users = array();
        while ($user = $result->fetch()) {
            $users.push($user);
        }
        return $users;
	}
	
	public function addUser($user_name, $pass, $first_name, $last_name, $email){
        $stmt = Vecni::$db->prepare("call add_user('$user_name', '$email', '$first_name', '$last_name', '$pass')");
		if($stmt->execute()){
			$this->start_session($stmt->fetch());
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
                $user->set_user_data(false, $data);
                array_push($users_data, $user);
            }
        }
        return $users_data;        
    }
    	
	public function start_session($data = ''){
		if(!empty($data)){
			$_SESSION['uid'] = $data; 	
		};
		if(isset($_SESSION['uid'])){
			$this->user_name = $_SESSION['uid']['user_name'];	//username address for user
			$this->first_name = $_SESSION['uid']['first_name'];	// first name 
			$this->last_name = $_SESSION['uid']['last_name'];	// last name 	
            //$this->dob = $_SESSION['uid']['dob'];
            $this->email = $_SESSION['uid']['email'];
            $this->profile_pic = $_SESSION['uid']['profile_pic'];
            $this->status = $_SESSION['uid']['is_active'];
            $this->is_admin = $_SESSION['uid']['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}else{
			return 0;
		}
	}
    
    public function set_user_data($is_current = true, $data = ''){
		if(!empty($data) && $is_current){
			$_SESSION['uid'] = $data[0]; 	
		}
		if(isset($_SESSION['uid']) && $is_current){
			$this->user_name = $_SESSION['uid']['user_name'];	//username address for user
			$this->first_name = $_SESSION['uid']['first_name'];	// first name 
			$this->last_name = $_SESSION['uid']['last_name'];	// last name 	
            //$this->dob = $_SESSION['uid']['dob'];
            $this->email = $_SESSION['uid']['email'];
            $this->profile_pic = $_SESSION['uid']['profile_pic'];
            $this->status = $_SESSION['uid']['is_active'];
            $this->is_admin = $_SESSION['uid']['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}else{
			$this->user_name = $data['user_name'];	//username address for user
			$this->first_name = $data['first_name'];	// first name 
			$this->last_name = $data['last_name'];	// last name 	
            //$this->dob = $data['dob'];
            $this->email = $data['email'];
            $this->profile_pic = $data['profile_pic'];
            $this->status = $data['is_active'];
            $this->is_admin = $data['is_admin'];
			$this->name = $this->first_name.' '.$this->last_name;
		}
	}
    
    public static function get_current_user(){
        $user = new User;
        $user->set_user_data();
        return $user;
    }
    
    public function set_user_info($attribute, $value){
        $this->$attribute = $value;
        array_push($this->changes, $attribute);
    }
    
    public function commitChnages(){
        $sql = "update profile set ";
        $first = true;
        foreach($this->changes as $changes){
            $_SESSION['uid'][$changes] = $this->$changes;
            $sql .= (($first)? "":", ").$changes." = '".$this->$changes."' ";
            $first = false;
        }
        $sql .= " where user_name = '".$this->user_name."'";
        if(self::$db->query($sql)){
            return 1;   
        }else{
            return 0;
        }        
    }
    
    public static function getFriends($condition = 1){
         self::$db = new Database();
		if($result = self::$db->fetch_query_results("select friend_id, first_name, last_name from friend_of as fof join profile as p on fof.friend_id = p.user_name where ".$condition)){
			return $result;
		}else{
			return 0;
		}
    }
	
	public function getUserBy($attribute){
		return (!empty($this->$attribute))? $this->$attribute:0;
	}
	
	public function isUserLogin($user_name){
		return ($this->user_name ===  $user_name)? 1:0;	
	}
    
    public function is_active(){
        return $this->is_active;   
    }
    
    public function is_admin(){
        return $this->is_admin;   
    }
	
	public function is_login($username = ''){
		return (isset($_SESSION['uid']['user_name']) && ($_SESSION['uid']['user_name'] ==  $this->user_name))? 1:0;	
	}
	
	public function log_out(){
        $stmt = Vecni::$db->prepare("update users set is_active = False where user_name = '$user_name'");
        if($stmt->execute()){
		     unset($_SESSION['uid']);
			 return 1;
		}else{
			return 0;
		}
	}
	
	public function removeUser($user_name){
		return ($this->is_login($user_name))? (self::$db->query("delete from users where user_name = '$user_name'"))? 1:0:0;
	}	
	
	public function userExists($user_name){
		return self::$db->recordExist("select count(*) > 0 from users where user_name = '$user_name'");
	}
    
    
    public static function current_user_name(){
        if(isset($_SESSION['uid'])){
            try{
                return $_SESSION['uid']['user_name'];
            }catch(Exception $e){
                #echo $e->getMessage();
                return false; 
            }
        }
        return false;
    }
}


?>