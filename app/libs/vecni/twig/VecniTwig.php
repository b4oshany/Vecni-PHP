<?php
namespace libs\vecni\twig;
use libs\vecni\Vecni as app;

/**
* Manage Vecni interaction with twig;
*/
class VecniTwig{
    private static $twig_autoloader;
    
    /**
    * Set twig autoloader.
    * @param string $path - Path of twig folder.
    */
    public static function setTwigAutoloader($path){
        self::$twig_autoloader = $path;
    }
    
    
    /**
    * Get twig autoloader.
    * @return string - Path to twig folder.
    */
    public static function register(){
        require_once self::$twig_autoloader;
        # register autloading package twig
        \Twig_Autoloader::register();
    }
}

?>
