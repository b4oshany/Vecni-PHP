<?php
namespace libs\vecni\libs;

class Cookie{
    use NamespaceTrait;

    /** @var DateTime $expire Expire date of the cookie. */
    public static $expire = null;
    /** @var str $domain Domain the cookie is valid on. */
    public static $domain = "";
    /* @param str $path The path on the server which of the cookie will
    *      be available from.
    */
    public static $path = "/";

    public static function init(){
        // Set the expire date to 15 days from the current day.
        // Note: 86400 is one day
        self::$expire = time() + (86400 * 15);
    }

    /**
    * Filter and sanitize variables, data or other values within the system.
    * @return mixed Sanitized data.
    */
    public static function FILTER_INPUT($var){
         return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    /**
     * Store cookie information to the client computer.
     * @param DateTime $expire Expire date of the cookie.
     * @param str $path The path on the server which of the cookie will
     *      be available from.
     * @param boolean $secure Indicate that the cookie should be only
     *      be accessible via HTTPS connections.
     * @param boolean $httponly Indicate whether the cookie should be only
     *      accessible by HTTP protocols. As a result, JavaScript and other
     * scripting language won't be able to access the cookie information.
     * @return boolean True if cookie has been set, else false.
     */
    public static function set($name, $value, $expire=0, $path=null,
                               $secure=false, $httponly=false){
        $expire = (!$expire)? self::$expire : $expire;
        $path = self::updatePath($path);
        $result = setcookie($name, $value, $expire, $path,
                         self::$domain, $secure,
                         $httponly);
        $_COOKIE[$name] = $value;
        return $result;
    }

    /**
     * Get cookie by cookie name.
     * @param str $name Name of the cookie.
     * @return mixed Cookie value.
     */
    public static function get($name){
        if(!empty($_COOKIE[$name]))
            return $_COOKIE[$name];
        return false;
    }

    /**
     * Update path to current host.
     * @return str Path of the cooke.
     */
    private static function updatePath($path){
        return ($path == "/" || empty($path))? self::$path : $path;
    }

    /**
     * Unset cookie.
     * @param str $name Name of the cookie.
     * @return str|bool cookie value if cookie is unset else false.
     */
     public static function remove($name, $path="/"){
        $path = self::updatePath($path);
        $cookie = self::get($name);
        if(!!$cookie && setcookie($name, '', time() - 3600, $path))
            return $cookie;
        return false;
     }
}

Cookie::init();

?>
