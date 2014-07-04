<?php 
session_start();
ini_set('display_errors',1); 
error_reporting(E_ALL);
require_once "etc/configs/Vecni.php";
include "main.ini.php";
use configs\Vecni;
Vecni::get_route();
?>
