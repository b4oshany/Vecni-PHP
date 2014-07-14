<?php
# define package usage
use configs\Vecni;
use libs\user\User;
use libs\university\University;
use libs\Response;

$vecni = new Vecni();
User::start_session();

$twig = Vecni::twig_loader();
$less = Vecni::less_loader();
Response::init();

# added global variables to twig
$twig->addGlobal("configs", $vecni->get_configs());
$twig->addGlobal("title", "Unversity Style");
if(User::is_login()){
    $twig->addGlobal("user", User::get_current_user());
}

#allow call to static functions
function staticCall($class, $function, $args = array()){
    if (class_exists($class) && method_exists($class, $function))
        return call_user_func_array(array($class, $function), $args);
    return null;
}
$twig->addFunction('staticCall', new Twig_Function_Function('staticCall'));

# compile css less files
$css_file = dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."gen"
                      .DIRECTORY_SEPARATOR."style.css";
$less_file = dirname(Vecni::$main_dir)
                      .DIRECTORY_SEPARATOR.Vecni::$css_dir
                      .DIRECTORY_SEPARATOR."less"
                      .DIRECTORY_SEPARATOR."style.less";
$style = $less->compileFile($less_file);
if(file_exists($css_file)){
    unlink($css_file);
}
$fp = fopen($css_file, 'w');
fwrite($fp, $style);
fclose($fp);
chmod($css_file, 0777); 

/**
Welcome:
    Navigational view that renders the welcome page to the user.
    This function is the default fall back function that
    have been registered in the system by default.
*/
function welcome(){
    global $twig;
    if(User::is_login()){
        return $twig->render("home.php",
                    array(
                        "html_class"=>"welcome"
                    ));
    }else{
        return $twig->render('welcome.php',
                      array(
                        "html_class"=>"welcome",                     
                        "title"=>"Welcome to Tattle Tale"                      
                      )                      
                  );
    }
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
    return $twig->render('404.php');
}

/**
* Sign in page for users
*/
Vecni::set_route("signin", "signin_require");
function signin_require($message=""){
    global $twig;
    return $twig->render('signin.php',
              array(
                "html_class"=>"signin",                     
                "title"=>"Signin Required",
                "message"=>$message
              )                      
          );
}
/**
* Registration page for users
*/
Vecni::set_route("registration", "reg_request");
function reg_request($message=""){
    global $twig;
    return $twig->render('registration.php');                         
}
/**
* Sign in processing for users
*/
Vecni::set_route("procsignin", "process_login");
function process_login(){
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username = $_POST['username'];
        $pass = $_POST['password']; 
        $status = User::login($username, $pass);
        if($status){
            return Response::json_response(200, $username);
        }else{
            return Response::json_response(204, "Login Failure");
        }     
    }  
}


/**
* Registration processing for users
*/
Vecni::set_route("procregister", "register");
function register(){
    global $user;
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['username']) && $_POST['password']){
        $uname = $_POST['username'];
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $status = User::register($uname, $pass, $fname, $lname, $email);
        if($status){
            return Response::json_response(200, $uname);
        }else{
            return Response::json_response(204, "Something went wrong");
        }             
    }
}


/**
* Log out users out of Tattle Tale
* @redirect page welcome
*/
Vecni::set_route("logout", "log_out");
function log_out(){
    global $twig;
    if(User::is_login()){
        User::log_out();  
        $twig->addGlobal("user", new User());
    }    
    Vecni::redirect();
}

?>
