<?php
namespace libs\vecni\vetwig;
use libs\vecni\Vecni as app;

/**
* Manage Vecni functions for twig;
*/
class TwigFunction{

    /**
    * Add functions to twig.
    * @param string $name - Name of the function.
    * @param mixed $function - A function can be a string, function or array
    *    consiting of the class name and method.
    */
    public static function addFunction($name, $function){
        $function = new \Twig_SimpleFunction($name, $function);
        app::$twig->addFunction($function);
    }

    public static function statiCall($class, $function, $args = array()){
        if (class_exists($class) && method_exists($class, $function))
            return call_user_func_array(array($class, $function), $args);
        return null;
    }

    /**
    * Add functions from a class to twig.
    * @param string $fn_name - Name of the function called by in twig.
    * @param string $cusstom_fn_name - Name of the static function or variable.
    *    consiting of the class name and method.
    */
    public static function addClassFunction($fn_name, $custom_fn_name=""){
        $custom_fn_name = $custom_fn_name or $fn_name;
        self::addFunction($fn_name, array(get_called_class(), $custom_fn_name));
    }
}

?>
