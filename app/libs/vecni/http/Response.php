<?php
namespace libs\vecni\http;
require_once "Request.php";
use libs\vecni\libs\NamespaceTrait;

abstract class Response{
    use NamespaceTrait;
    public static function init(){
        if(!isset($_SESSION["access_key"])){
            $_SESSION["access_key"] = uniqid('vecni_');
        }
    }

    /**
    * Return a basic json response wit a status code and message.
    * @param mixed $status Resposne status.
    * @param mixed $message Response text.
    * @return JSON data.
    */
    public static function json_response($status_code=200, $message="ok"){
        header('Content-Type: application/json');
        return json_encode(array('status'=>$status_code,
                          'message'=>$message));
    }

    public static function json_feed($message=""){
        header('Content-Type: application/json');
        return json_encode($message);
    }

    public static function add_header($header, $message){
        header("$header: $message");
    }

    public static function abort($message = "Not Found", $status_code=404, $raw_output=false){
        header("Connection: close", true);
        header("Message: $message");
        if(Request::is_async() && is_array($message)){
            echo self::json_response($status_code, $message);
        }
        if($raw_output){
            echo $message;
        }
        header("HTTP/1.0 $status_code $message");
        die();
    }
}


?>
