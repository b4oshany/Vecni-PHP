<?php require_once "etc/config/website.settings.php" ?>
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
        <form name="login" method="post" action="index.php">
            <img src="static/img/user-3.png">
            <div class="input-group">
              <span class="input-group-addon">@</span>
              <input type="text" class="form-control" name="user" placeholder="Username">
            </div>
            <div class="input-group">
              <span class="input-group-addon">*</span>
              <input type="password" class="form-control" name="pass" placeholder="Password">
            </div>
            <input type="submit" value="Sign In" class="btn btn-primary btn-lg">
        </form>   
     </div>  
  </div>
  <div id="slider">
  
  </div>
  <script src="static/js/jquery.js"></script>
  <script src="static/bootstrap/js/bootstrap.min.js"></script>   
  <script src="static/js/home.js"></script>        
  </body>
</html>
