<?php
namespace libs\vecni\libs;

/**
* Format data to specific Objects.
*/
class ObjectFormatter{
  
    /**
    * Convert dates string, yyyy-mm-dd to DateTime obejct.
    * @param string $date_string Date string.
    * @return \DateTime.
    */
    public static function to_DateTime($date_string){
        return \DateTime::createFromFormat('Y-m-d H:i:s', $date_string);
    }
}

?>
