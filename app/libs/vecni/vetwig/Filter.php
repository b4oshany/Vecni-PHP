<?php
namespace libs\vecni\vetwig;
use libs\vecni\Vecni as app;

/**
* Manage Vecni filters for twig;
*/
class Filter{

    /**
    * Add filters to twig.
    * @param string $name - Name of the filter.
    * @param mixed $filter - A filter can be a string, function or array
    *    consiting of the class name and method.
    */
    public static function addFilter($name, $filter){
        $filter = new \Twig_SimpleFilter($name, $filter);
        app::$twig->addFilter($filter);
    }

    public static function staticCall($class, $function, $args = array()){
        if (class_exists($class) && method_exists($class, $function))
            return call_user_func_array(array($class, $function), $args);
        return null;
    }

    public static function relative($string=""){
        return remove_extra_slashes(app::$host."/$string");
    }

    public static function htmlchars($str=""){
        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }

    /**
    * Add filters from a class to twig.
    * @param string $name - Name of the filter.
    * @param string $filter - Name of the static function or variable.
    *    consiting of the class name and method.
    */
    public static function addClassFilter($filter_name, $fn_name=""){
        self::addFilter($filter_name, array(get_called_class(), $fn_name));
    }

    /**
    * Register the default filters for Vecni.
    */
    public static function register(){
        self::addFilter("relative", array(get_called_class(), "relative"));
        self::addFilter("htmlchars", array(get_called_class(), "htmlchars"));
    }

}

?>
