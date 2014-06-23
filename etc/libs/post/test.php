<?php
require_once "setup.php";
use modules\mybook\post\Controller;
use configs\Websets;
Websets::startDatabase();
echo Controller::getPosts();
?>