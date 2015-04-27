<?php
namespace libs\vecni\vetwig;

/**
* Manage Vecni interaction with twig;
*/
trait TwigTrait{

    /**
     * Register persistent data to be added to twig.
     * The twig data will be added during the next request made by the
     * client.
     * @param array $data Array of twig data.
     */
    public static function registerTwigPersistGlobals(array $data){
        $_SESSION["twig_persist_data"] = $data;
    }

    /**
     * Add persistent data to twig.
     */
    public static function addTwigPersistGlobals(){
        $session = static::$session;
        if($data = $session::get("twig_persist_data")){
            foreach($data as $key => $value){
                static::$twig->addGlobal($key, $value);
            }
        }
    }

    /**
     * Remove persistent data from twig;
     */
    public static function removeTwigPersistGlobals(){
        $session = self::$session;
        $session->remove("twig_persist_data");
    }
}

?>
