<?php
namespace libs\vecni\http;

/**
* Manage caching
*/
class Cache{
    /**
    * Disable caching.
    */
    public static function disable_caching(){
        header("Expires: 0");
        header("Last-Modified: " . date("D, d M Y H:i:s") );
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}
