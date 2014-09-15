<?php
namespace libs\user;
require_once ".autoload.php";
use configs\Vecni;
use libs\mongodb\Model;
use libs\schedule\Calendar;

/**
* @package user
* @property string $username - username of the user
* @property string $first_name - first name of the user
* @property string $last_name - last name of the user
* @property string $email - email of the user
* @property string $status - status of user (new, regular, paid)
* @method login($email, $password) - login user invoking
* @method register($email, $password) - register user
* @method login_with_social_network($email, $account_type, $account_id) - login with social network account
* @method is_admin() - check if a user is an admin or not
* @method get_calendars($query) - get all calendar for that user
* @method get_default_calendar() - get the default calendar for the user
* @method log_out() - logout the current user
*/

class User extends Model{
    public static $collection = "user";

    protected $password;        // password of user
    public $username;           // username of user
    public $first_name;         // user first name
    public $last_name;          // user last name
    public $email;              // user email
    public $is_login;           // user login status
    public $status;             // user registration status
    public $dob;                // user date of birth

    /**
    * __callstatic is triggered when invoking inaccessible methods in an static context
    * This method will initiate the mongodb connection and select the desired database
    * In addition, it will initiate the collection model for the user data.
    */
    public static function setUp(){
        parent::setUp();
        $collection = self::$collection;
        self::$model = self::$mongodb->$collection;
    }

    /**
    * Set the collection settings for monogodb
    * such as the unique attributes in the collection
    */
    public static function collectionSettings(){
        self::$mongodb->user->createIndex(
            array("username"=>1,
                  "email"=>1
                 ),
            array("unique"=>1)
        );
    }

    /**
    * Login user with their normal user account
    * @param string $email - email to be used for login
    * @password string $password - password to be used for login
    * @return bool - true if the user is success logged in and session has been started
    * false if there was an error during login
    */
    public static function login($email, $password){
        $user = self::find_one(array("email"=>$email,
                                         "password"=>md5($password)
                                         ),
                                      array("password" => 0)
                                     );
        if(!empty($user)){
            $user->update(array('$set'=>array("is_login"=>true)));
            $user->is_login = true;
            return self::start_session($user->to_array());
        }else{
            return 0;
        }
    }

    /**
    * Normal registration for user account
    * @param string $email - email to be used for registration
    * @param string $passowrd - password to be used for registration
    * @return bool - true if registration was success and session has been started
    * false if their was a error or failure to do soundex
    */
    public function register($email, $password){
        $user_exist = self::$model->findOne(array('email'=>$email));
        if(empty($user_exist)){
            $this->email = $email;
        }else{
            return 0;
        }
        $this->password = md5($password);
        $this->is_login = true;
        $this->is_admin = false;
        $this->status = "new";
        $this->date_joined = new \DateTime("NOW");
        $user_id = (string) $this->save();
        $this->id = $user_id;
        if(self::start_session($this->to_array())){
            return 1;
        }
        return 0;
    }

    public function enforce_constraints(){
        $this->dob = self::cast("DateTime", (array) $this->dob);
        $this->date_joined = self::cast("DateTime", (array) $this->date_joined);
    }

    /**
    * Special login or registration of user with their social network account.
    * This function copies the basic user information from their social network into
    * the database of this application
    * @method login_with_social_network($email, $account_type, $account_id)
    * @param string $email - email of user social netowrk account
    * @param string $accounty_type - type of social network account
    * @param mixed $account_id - the id of their social network account
    * @return bool - true if user is success login with thier social network account
    * and the session has started else false if their is any login failure
    */
    public function login_with_social_network($email, $account_type, $account_id){
        $user_exist = self::$model->findOne(array('email'=>$email));
        if(!empty($user_exist)){
            self::$model->update(array("email"=>$email),
                                array('$set'=>array($account_type=>$account_id)
                                     ));
            $this->populate($user_exist);
            $this->$account_type = $account_id;
            $this->id = $user_exist["_id"];
            $this->email = $email;
        }else{
            $this->email = $email;
            $this->$account_type = $account_id;
            $this->is_login = true;
            $this->is_admin = false;
            $this->status = "new";
            $user_id = (string) $this->save();
            $this->id = $user_id;
        }
        if(self::start_session($this->to_array())){
            return 1;
        }
        return 0;
    }

    /**
    * Start the session of for the current user
    * @param array $data - takes in the user object array data
    * @return bool - true if the user session has been set else false
    */
    public static function start_session(array $data = null){
        if(!empty($data)){
            $_SESSION['uid'] = $data;
            return 1;
        }
        return 0;
    }

    /**
    * Get the current logged in user via the session variable
    * @return User|null - current user object if user session has set, else null
    */
    public static function get_current_user(){
        if(isset($_SESSION['uid'])){
            return self::load($_SESSION['uid']);
        }else{
            return null;
        }
    }

    /**
    * Get the current logged in user id
    * @return string - current user id
    */
    public static function get_current_user_id(){
        if(isset($_SESSION['uid'])){
            return $_SESSION['uid']["id"];
        }
    }

    /**
    * Get the current logged in user via the session variable
    * @return User|null - current user object if user session has set, else null
    */
    public static function get_current_user_db(){
        if(isset($_SESSION['uid']["id"])){
            $user_id =  $_SESSION['uid']["id"];
            return self::get_by_id($user_id);
        }
    }

    /**
    * check if the user is logged in
    * @param string $user_id - mongodb id for user to be check for login
    * if the $user_id is provided, then it will use it, else it will check for the current logged in user
    * @return bool true if the user is logged in false if not
    */
    public static function is_login($username = null){
        if(isset($username)){
            return ((isset($_SESSION['uid']['email'])) && ($_SESSION['uid']['email'] ==  $username));
        }
        return isset($_SESSION['uid']['email']);
    }

    /**
    * Logout user out of the current session
    * @return bool - true if logout was successful false if not
    */
    public static function log_out(){
        if(isset($_SESSION['uid'])){
            $user = self::get_current_user_db();
            unset($_SESSION['uid']);
            $user->update(array('$set'=>array("is_login"=>false)));
            return 1;
        }
        return 0;
    }

    /**
    * Get the default calendar for the current user
    * @param array $query - mongodb query creteria to be used to fetch user calendar
    * @param Calendar - user default calendar
    */
    public function get_default_calendar(){
        $calendar = Calendar::find_one(
            array(
                "creator"=>$this->get_MongoId(),
                "title"=>"General"
            )
        );
        if(!empty($calendar)){
            return $calendar;
        }else{
            $calendar = Calendar::create("General");
            $calendar->create_event("My Birthday", $this->dob, "Happy Birthday $this->first_name");
            return $calendar;
        }
    }

    /**
    * Get all of the current user calendars
    * @param array $query - condition to get all user calendars
    * @return array - list of Calendar object for the current user
    */
    public function get_calendars(array $query = null){
        if(!isset($query)){
            $query = array("creator"=>$this->get_MongoId());
        }
        return Calendar::find($query);
    }
}

User::setUp();
User::collectionSettings();
?>
