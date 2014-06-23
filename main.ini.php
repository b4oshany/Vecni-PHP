<?php
require_once "etc/configs/Vency.php";
use configs\Vency;
$vency = new Vency();

function welcome(){
    global $vency;
    $vency->render("home.php");
}


?>