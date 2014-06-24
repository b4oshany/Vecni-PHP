<?php
# include packages
require_once "etc/configs/Vecni.php";
require_once 'etc/libs/twig/lib/Twig/Autoloader.php';
require_once 'etc/libs/less/lessc.inc.php';

# define package usage
use configs\Vecni;

$Vecni = new Vecni();

# register autloading package twig
Twig_Autoloader::register();
# get the templates folder and prepare template rendering
$loader = new Twig_Loader_Filesystem("./templates");
$twig = new Twig_Environment($loader);

# compile css less files
$less = new lessc;
$less->checkedCompile(dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."less"
                      .DIRECTORY_SEPARATOR."style.less",
                     dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."src"
                      .DIRECTORY_SEPARATOR."style.css"
                     );

/**
Welcome:
    Navigational view that renders the welcome page to the user.
    This function is the default fall back function that
    have been registered in the system by default.
*/
function welcome(){
    global $twig;
    echo $twig->render('welcome.php');
}

/**
error:
    Navigational view that renders the error page to the user when
    there is a server error or a page is not found.
    This function is the default fall back function that
    have been registered in the system by default.
*/
function error(){
    global $twig;
    echo $twig->render('404.php');
}



?>