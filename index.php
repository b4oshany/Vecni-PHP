<?php
session_start();
require_once "app/plugins/autoload.php";

use libs\vecni\Vecni as app;
app::init(__FILE__);
include "main.ini.php";
app::get_route();
?>
