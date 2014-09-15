<?php
session_start();
require_once ".autoload.php";
include_once "etc/configs/settings.ini.php";
include "main.ini.php";
use libs\vecni\Vecni;
Vecni::get_route();
?>
