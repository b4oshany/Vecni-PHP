<?php
namespace libs\vecni\twig;
use libs\vecni\Vecni as app;

VecniTwig::register();

/**
* Manage Vecni filters for twig;
*/
class Filter{

    /**
    * Add filters to twig.
    * @param string $name - Name of the filter.
    * @param mixed $filter - A filter can be a string, function or array
    *    consiting of the class name and method.
    * @uses 
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
        return app::getHost()."/$string";
    }
    
    /**
    * Register the default filters for Vecni.
    */
    public static function register(){
        /*
        # Allow call to static functions.
        app::$twig->addFunction(new \Twig_SimpleFunction("static",
            function($class, $function, $args=array()){
                return self::staticClass($class, $function, $args);
        }));*/
        self::addFilter("relative", array(__CLASS__, "relative"));
    }

}

?>
