<?php
namespace libs\vecni;
require_once "Object.php";

class Session extends Object{
    /**
    * Set the session data for the current and previous request.
    */
    public static function start(){
        if(!isset($_SESSION["ACCESS_KEY"])){
            $_SESSION["ACCESS_KEY"] = uniqid('vecni_');
        }
        $previous = null;
        if(isset($_SESSION["APP_SESSION"]) && isset($_SESSION["APP_SESSION"]["CURRENT"])){
            $previous = $_SESSION["APP_SESSION"]["CURRENT"];
        }
        $_SESSION["APP_SESSION"] = array(
            "CURRENT"=>array(
                "time"=>date("m/d/Y H:i:s"),
                "client"=>array(
                "ip"=>$_SERVER["REMOTE_ADDR"]
                )
            ),
            "PREVIOUS"=>$previous
        );
    }

    /**
    * Set a session variable.
    * @param string $key - name of the session variable.
    * @param mixed $value - value of the session variable.
    */
    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    /**
    * Get a session variable.
    * @param string $key - name of the session variable.
    * @return mixed - value of the session variable.
    */
    public static function get($key, $default=false){
        return (isset($_SESSION[$key]))? $_SESSION[$key]: $default;
    }

    /**
    * Get the current session data.
    * @param string $key - name of the current session varaible.
    * @return mixed - value of the current session variable.
    */
    public static function current_data($key, $default=null){
        $stock = $_SESSION["APP_SESSION"]["CURRENT"];
        return (!empty($stock[$key]))? $stock[$key]: $default;
    }
    /**
    * Get the previous session data.
    * @param string $key - name of the previous session varaible.
    * @return mixed - value of the previous session variable.
    */
    public static function previous_data($key, $default=null){
        $previous = $_SESSION["APP_SESSION"]["PREVIOUS"];
        return (!empty($previous[$key]))? $previous[$key]: $default;
    }
}
