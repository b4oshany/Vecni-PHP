<?php 
require_once "etc/__autoload.php";
require_once "page/page_handler.php";
use etc\configs\Websets;
?>
<!DOCTYPE html>
<html lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">
        <title>Welcome To Fero Inc.</title>
        <link rel="stylesheet" type="text/css" href="static/css/home.css" >
        <link rel="stylesheet" type="text/css" href="static/bootstrap/css/bootstrap.css">
    </head>
  <body > 
  <div id="overcast">
    <div class='wrapper'>        
     </div>  
  </div>
  <?php
    require_once $page 
      ?>
  </body>
</html>
