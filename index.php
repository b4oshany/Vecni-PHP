<?php
session_start();
require_once "app/libs/vecni/Autoloader.php";
include "main.ini.php";
use libs\vecni\Vecni;
Vecni::get_route();
?>
