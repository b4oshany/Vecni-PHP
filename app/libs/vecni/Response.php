<?php
namespace libs\vecni;
require_once "Request.php";

class Response{
    public static function init(){
        if(!isset($_SESSION["access_key"])){
            $_SESSION["access_key"] = uniqid('vecni_');
        }
    }

    public static function json_response($status_code=200, $message="ok"){
        header('Content-Type: application/json');
        return json_encode(array('status'=>$status_code,
                          'message'=>$message));
    }

    public static function json_feed($message=""){
        header('Content-Type: application/json');
        return json_encode($message);
    }

    public static function abort($message = "Something went wrong"){
        header("Connection: close", true);
        if(Request::is_async()){
            header("Message: $message");
        }else{
            echo $message;
        }
        header("HTTP/1.0 404 Not Found");
        die();
    }
}


?>
