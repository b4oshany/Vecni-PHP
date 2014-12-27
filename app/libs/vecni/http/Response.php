<?php
namespace libs\vecni\http;
use libs\vecni\Vecni as app;
require_once "Request.php";

class Response{
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

    public static function abort($message = "Not Found", $status_code=404, $status_text="Not Found"){
        header("Connection: close", true);
        header("Message: $message");
        if(Request::is_async()){
            echo self::json_response($status_code, $message);
        }
        header("HTTP/1.0 $status_code $status_text");
        die();
    }
}


?>
