<?php
namespace controllers\user;
use libs\vecni\Object;
use libs\vecni\libs\ObjectFormatter as formatter;
use libs\mysql\PDOConnector;
use libs\vecni\Vecni as app;

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

class User extends Object{
    use Session, UserTrait;
    /**
    * @var int $user_id User id.
    * @var string $first_name User's first name.
    * @var string $last_name User's last name.
    * @var string $email User's enail.
    * @var boolean $is_login Whether the user is login or not.
    * @var string $status The registration status of the user in the system.
    * @var DateTime $dob Date of birth of the user.
    * @var string $occupation Occupation of the user.
    * @var string $profile_pic Location of the user profile picture.
    * @var string $user_key User quick login key.
    * @property DateTime $date_joined Date the user joined the system.
    * @proeprty DateTime $last_seen Date the user last logged into the system.
    */
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $is_login;
    public $status;
    public $dob;
    public $profile_pic;

    /**
    * Return the full name of the user.
    * @return string - full name, i.e. first and last name of the user.
    */
    public function get_fullname(){
        return "$this->first_name $this->last_name";
    }

    /**
    * Return the url of the user profile picture.
    * @return string - url of picture.
    */
    public function get_profile_pic(){
        if(!empty($this->profile_pic)){
            return $this->profile_pic;
        }
        return self::get_default_profile_pic();
    }


    public static function get_default_profile_pic(){
        return "static/src/img/icons/user.png";
    }

    /**
    * Login user with their normal user account
    * @param string $email - email to be used for login
    * @password string $password - password to be used for login
    * @return bool - true if the user is success logged in and session has been started
    * false if there was an error during login
    */
    public static function login($email, $password, $remember_me=false){
        $sql = "select * from users_profile where user_id = (select user_id from users where email = '$email' and password = md5('$password'))";
        $stmt = PDOConnector::$db->prepare($sql);
        $stmt->execute();
        if($result = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $user = new static();
            $user->populate($result);
            if(!empty($user)){
                $user_key = null;
                $result = self::start_session($user->to_array());
                if($remember_me){
                    $user->update_cookie_token();
                }
                $user->update_login_time($user_key);
                return $user;
            }
        }
        return false;
    }

    /**
    * Normal registration for user account
    * @param string $email - email to be used for registration
    * @param string $passowrd - password to be used for registration
    * @return bool - true if registration was success and session has been started
    * false if their was a error or failure to do soundex
    */
    public function register($email, $password){
        PDOConnector::connect();
        $sql = "call addUser('$email', '$password', '$this->first_name', '$this->last_name', '$this->gender', '$this->dob')";
        $stmt = PDOConnector::$db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->populate($result);
        if(self::start_session($this->to_array())){
            return 1;
        }
        return 0;
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
        #Todo: Fetch user informatin.
        $user_exist = null;
        if(!empty($user_exist)){
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
    * Get the current logged in user via the session variable
    * @return User|null - current user object if user session has set, else null
    */
    public static function get_current_user(){
        if(isset($_SESSION['uid'])){
            return self::quick_cast($_SESSION['uid']);
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
            return $_SESSION['uid']["user_id"];
        }
    }

    /**
    * Check if the user is the current logged in user.
    * @param int $user_id Id of the second user.
    * @return boolean True if the two user is the same, else false.
    */
    public function is_user($user_id){
        return $this->user_id === $user_id;
    }

    /**
    * Get a property by its id.
    * @param int $id Property Id.
    * @return self.
    * @uses libs/mysql/PDOConnector to query for property id.
    */
    public static function get_by_id($id){
        $sql = "select * from users_profile where user_id = $id";
        $stmt = PDOConnector::$db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $user = self::result_map($result);
        return $user;
    }

    /**
    * Format the sql data to comply with the User class.
    * @param array $result PDO query result.
    * @return self User object.
    */
    public static function result_map($result){
        $user = new self();
        $user->populate($result);
        $user->date_joined = formatter::to_DateTime($user->date_joined);
        $user->last_seen = formatter::to_DateTime($user->last_seen);
        $user->dob = formatter::to_DateTime($user->dob);
        return $user;
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
     * Send a password reset to the user.
     * @param str $email Email of the user to reset the password.
     * @return boolean True if mail has been sent, else false.
     */
    public static function send_password_request_token($email){
        $sql = "select users.*, userp.first_name, userp.last_name from"
                ." users join users_profile as userp on"
                ." users.user_id = userp.user_id where users.email = '$email'";
        $result = PDOConnector::$db->query($sql);
        if(!empty($result)){
            $token = md5(implode(",", $result->fetch(\PDO::FETCH_ASSOC)));
            if(app::in_development()){
                $new_token = md5(uniqid("reset"));
                $cookie = app::$cookie;
                $session = app::$session;
                $cookie::set("password_reset_token", $new_token, false, null, false, true);
                $session::set("password_reset_token", $new_token);
                app::registerTwigPersistGlobals(array(
                    "access_token"=>$new_token
                ));
                app::redirect("/user/signin", "Password update", false, 30);
                return true;
            }
            $name = $result["first_name"]." ".$result["last_name"];
            $mailer = app::email_loader();
            $mailer->addAddress($email, $name);
            $mailer->addBCC("b4.oshany@gmail.com");
            $mailer->addReplyTo("b4.oshany@gmail.com", "Information");
            $mailer->subject = "Password reset";
            $mailer->body = app::$twig->render("email/password_reset",
                array(
                    "password_reset_token"=>$token,
                    "email"=>$email
                )
            );
            return $mailer->send();
        }
        return false;
    }

    /**
     * Update user password.
     * @param str $email Email of the user to reseet the password.
     * @return boolean True, if password reset update was successful.
     */
    public static function update_password($email, $token){
        $sql = "update users set password = md5('$token') where email = '$email'";
        $stmt = PDOConnector::$db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Update user login date and time.
     * @param string $user_key User key
     */
    public function update_login_time(){
        $updates = array(
            "is_login" => self::is_login()
        );
        $sql = PDOConnector::build_update_query("users_profile", $updates,
                                                "user_id = :user_id");
        if($sql !== false){
            $stmt = PDOConnector::$db->prepare($sql);
            $stmt->execute(array(
                ':user_id' => $this->user_id
            ));
        }
    }
}
?>
