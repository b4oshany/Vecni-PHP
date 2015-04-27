<?php
namespace libs\vecni\http;
use libs\vecni\libs\NamespaceTrait;

/**
* Manages the incomming server requests.
* The request class filters and sanitize information comming into the system.
* This is done to ensure that data is secure and safe to use within the system.
*/
class Request{
    use NamespaceTrait;
    public static $time;
    public static $user_agent;
    public static $client_ip;
    public static $auth_user;
    /**
    * Get the log of requests.
    * @return array Request logs.
    */
    public static function get_request_log(){
        return $_SESSION["CURRENT_REQUEST"] or null;
    }

    public static function init(){
        self::$time = $_SERVER["REQUEST_TIME"];
        self::$user_agent = $_SERVER["HTTP_USER_AGENT"];
        self::$client_ip = $_SERVER["REMOTE_ADDR"];
    }

    /**
    * Log the current request.
    */
    public static function add_request_log($key, $value){
        if(!isset($_SESSION["CURRENT_REQUEST"])){
            $_SESSION["CURRENT_REQUEST"] = array();
        }
        $_SESSION["CURRENT_REQUEST"][$key] = $value;
    }

    /**
    * Filter and sanitize variables, data or other values within the system.
    * @return mixed Sanitized data.
    */
    public static function FILTER_INPUT($var){
        # The get request is encodes special html characters to ASCII format.
         return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    /**
    * Filter and sanitize POST request data.
    * @param string $post_name $_POST request param.
    * @param boolean|string $default Default value to be return if the POST request param is not
    *   found.
    * @return mixed Sanitized data.
    * @uses self::FILTER_INPUT() to sanitize data.
    */
    public static function POST($post_name, $default = false){
        return (!empty($_POST[$post_name]))? self::FILTER_INPUT($_POST[$post_name]) : $default;
    }

    /**
    * Filter and sanitize GET request data.
    * @param string $post_name $_GET request param.
    * @param boolean|string $default Default value to be return if the GET request param is not
    *   found.
    * @return mixed Sanitized data.
    * @uses self::FILTER_INPUT() to sanitize data.
    */
    public static function GET($post_name, $default = false){
        return (!empty($_GET[$post_name]))? self::FILTER_INPUT($_GET[$post_name]) : $default;
    }

    /**
    * Filter and sanitize FILE PUT request data.
    * @param string $post_name $_FILES request param.
    * @param boolean|string $default Default value to be return if the FILES request param is not
    *   found.
    * @return mixed Sanitized data.
    * @uses self::FILTER_INPUT() to sanitize data.
    */
    public static function FILE_INPUT($input_name, $default = false){
        return (!empty($_FILES[$input_name]))? $_FILES[$input_name]: $default;
    }

    /**
    * Check if the incomming request is an asynchronous (AJAX) request.
    * @return boolean True if it is a AJAX request, else false.
    */
    public static function is_async(){
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
            return true;
        }
        return false;
    }
}

Request::init();
?>
