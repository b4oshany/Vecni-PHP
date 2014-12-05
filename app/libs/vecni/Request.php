<?php
namespace libs\vecni;

class Request{
    public static function init(){
        if(!isset($_SESSION["access_key"])){
            $_SESSION["access_key"] = uniqid('vecni_');
        }
    }

    public static function POST($post_name, $return = false){
        return (!empty($_POST[$post_name]))? $_POST[$post_name] : $return;
    }

    public static function GET($post_name, $return = false){
        return (!empty($_GET[$post_name]))? $_GET[$post_name] : $return;
    }

    public static function set($object, $data){
        if(!empty($data)){
            $object = $data;
            return $object;
        }
    }

    public static function is_async(){
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
            return true;
        }
        return false;
    }
}


?>
