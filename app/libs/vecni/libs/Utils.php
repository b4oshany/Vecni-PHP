<?php

ini_set('display_errors',1);
ini_set('html_errors',1);

/**
* Dump variable data.
* @param mixed $variable Variable to be dumped.
* @param boolean|string String if do_echo is false, else fale.
*/
function pretty_print($variable){
    echo "<pre>";
    print_r($variable);
    echo "</pre>";
}



/**
* Remove extra slashes from path.
* @return str Path without any extra slashes.
*/
function remove_extra_slashes($path){
    return preg_replace("/\/{2,}/", "/", $path);
}

?>