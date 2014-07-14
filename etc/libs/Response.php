<?php
namespace libs;
require "__autoload.php";

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
}


?>