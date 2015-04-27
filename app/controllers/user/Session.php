<?php
namespace controllers\user;
use libs\vecni\Vecni as app;
use libs\mysql\PDOConnector;

trait Session{
    /** @var str $cookie Name of the user cookie key */
    private static $cookie_key = "usrkey";

    /**
     * Login via user key.
     */
    public static function login_with_user_key(){
        $cookie = app::$cookie;
        if($user_key = $cookie::get(self::$cookie_key)){
            $sql = "select * from users_profile where user_key = '$user_key'";
            $user = new self();
            $stmt = PDOConnector::$db->prepare($sql);
            $stmt->execute();
            if($result = $stmt->fetch(\PDO::FETCH_ASSOC)){            
                $user->populate($result);
                if(!empty($user)){
                    $result = self::start_session($user->to_array());
                    $user->update_login_time();
                    return $user;
                }
            }
            return false;
        }
    }

    /**
     * Generate and update user access token.
     * An access token can be a cookie value or a oauth value.
     * @param str $token_type Type of access token.
     */
    private function update_cookie_token(){    
        $cookie = app::$cookie;
        $request = app::$request;
        if(self::is_login()){
            $token = $this->generate_token();    
            $client_ip = $request::$client_ip;
            // Set cookie to be created only via HTTP request only.
            $cookie::set(self::$cookie_key, $token, false, null, false, true);
            $sql = "insert into users_tokens(access_token, user_id, access_from)
                    values($this->user_id, '$token', '$client_ip')";
            $stmt = PDOConnector::$db->prepare($sql);
            return $stmt->execute();
        }else{
            $token = $cookie::remove(self::$cookie_key);
            if(!$token)
                return false;
            $sql = "delete from users_tokens where user_id = $this->user_id and acess_token = '$token' and token_type = 'cookie'";
            $stmt = PDOConnector::$db->prepare($sql);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Generate the user key.
     * @return str User key.
     */
    private function generate_token(){
        $prefix = $this->first_name."&".$this->user_id;
        $uid = uniqid($prefix);
        return md5($uid);
    }

    /**
    * check if the user is logged in
    * @param string $user_id - mongodb id for user to be check for login
    * if the $user_id is provided, then it will use it, else it will check for the current logged in user
    * @return bool true if the user is logged in false if not
    */
    public static function is_login($username = null){
        if(isset($username)){
            return ((isset($_SESSION['uid']['user_id'])) && ($_SESSION['uid']['user_id'] ==  $username));
        }
        return isset($_SESSION['uid']);
    }

    /**
    * Logout user out of the current session
    * @return bool - true if logout was successful false if not
    */
    public static function log_out(){
        if(isset($_SESSION['uid'])){
            $user = self::get_current_user();
            unset($_SESSION['uid']);
            $user->update_cookie_token();
            $user->update_login_time();
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
}
