<?php
# include packages
require_once 'etc/libs/twig/lib/Twig/Autoloader.php';
require_once 'etc/libs/less/lessc.inc.php';

# define package usage
use configs\Vecni;
use libs\user\User;


$vecni = new Vecni();
$user = new User();
$user->start_session();

# register autloading package twig
Twig_Autoloader::register();
# get the templates folder and prepare template rendering
$loader = new Twig_Loader_Filesystem("./templates");
$twig = new Twig_Environment($loader);

# added global variables to twig
$twig->addGlobal("configs", $vecni->get_configs());
$twig->addGlobal("title", "Unversity Style");
if($current_user = User::current_user_name()){
    $user = User::get_current_user();
    $twig->addGlobal("user", $user);
}

#allow call to static functions
function staticCall($class, $function, $args = array()){
    if (class_exists($class) && method_exists($class, $function))
        return call_user_func_array(array($class, $function), $args);
    return null;
}
$twig->addFunction('staticCall', new Twig_Function_Function('staticCall'));

# compile css less files
$less = new lessc;
$css_file = dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."gen"
                      .DIRECTORY_SEPARATOR."style.css";
$less_file = dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."less"
                      .DIRECTORY_SEPARATOR."style.less";
$style = $less->compileFile($less_file);
file_put_contents($css_file, $style);

/**
Welcome:
    Navigational view that renders the welcome page to the user.
    This function is the default fall back function that
    have been registered in the system by default.
*/
function welcome(){
    global $twig, $user;
    echo $twig->render('welcome.php',
                      array(
                        "html_class"=>"welcome",                     
                        "title"=>"Welcome to Tattle Tale"                      
                      )                      
                  );
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

Vecni::set_route("procsignin", "process_login");
function process_login(){
    global $user;
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username = $_POST['username'];
        $pass = $_POST['password']; 
        $status = $user->login($username, $pass); 
        if($status){
            header('Content-Type: application/json');
            $json = array('status'=>200, 'message'=>$username);
            echo json_encode($json);
        }else{
            echo json_encode(array('status'=>204, 'message'=>"Login Failure"));
        }     
    }  
}

Vecni::set_route("procregister", "register");
function register(){
    global $user;
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['username']) && $_POST['password']){
        $uname = $_POST['username'];
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $status = $user->addUser($uname, $pass, $fname, $lname, $email);
        if($status){
            header('Content-Type: application/json');
            $json = array('status'=>200, 'message'=>$uname);
            echo json_encode($json);
        }else{
            echo json_encode(array('status'=>204, 'message'=>"something went wrong"));
        }             
    }
}

Vecni::set_route("logout", "log_out");
function log_out(){
    global $twig, $user;
    if($user->is_login()){
        $user->log_out();  
        $twig->addGlobal("user", new User());
    }    
    echo $twig->render('welcome.php',
                      array(
                        "html_class"=>"welcome",                     
                        "title"=>"Welcome to Tattle Tale"                      
                      )                      
                  );
}


Vecni::set_route("tryout", "trya");
function trya(){
    try {        
        foreach(Vecni::$db->query('SELECT * from users') as $row) {
            print_r($row);
        }
        Vecni::$db = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

?>