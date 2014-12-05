<?php
namespace libs\vecni\http;

class Request{
    public static function init(){
        if(!isset($_SESSION["access_key"])){
            $_SESSION["access_key"] = uniqid('vecni_');
        }
    }
    
    private static function filter_input($filter_type, $var){
         return filter_input($filter_type, $var, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_ENCODED);
    }

    public static function POST($post_name, $return = false){
        $data = self::filter_input(INPUT_POST, $post_name);
        return (!empty($_POST[$post_name]))? $data : $return;
    }

    public static function GET($post_name, $return = false){
        $data = self::filter_input(INPUT_GET, $post_name);
        return (!empty($_GET[$post_name]))? $data : $return;
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
