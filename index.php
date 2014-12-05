<?php
session_start();
require_once "app/libs/vecni/Autoloader.php";
use libs\vecni\Vecni;
Vecni::init(__FILE__);
include "main.ini.php";
Vecni::get_route();
?>
