<?php
require_once "setup.php";
use modules\mybook\group\Controller;
use configs\Websets;
Websets::startDatabase();
echo Controller::addGroup("ohomes","ohomes", "b4oshany");
?>