<?php
session_start();
require_once ".autoload.php";
include "main.ini.php";
use libs\vecni\Vecni;
Vecni::get_route();
?>
