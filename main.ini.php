<?php
# define package usage
use libs\user\User;
use libs\university\University;
use libs\university\Faculty;
use libs\vecni\Response;
use libs\vecni\Request;
use libs\vecni\Vecni;
use libs\schedule\Calendar;

$vecni = new Vecni();
User::start_session();

$twig = Vecni::twig_loader();
include_once "etc/configs/settings.ini.php";
$less = Vecni::less_loader();
Response::init();

# added global variables to twig
$twig->addGlobal("config", $vecni->get_configs());
$twig->addGlobal("host", Vecni::$host);
$twig->addGlobal("title", "Tattle Tale");
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
Vecni::set_route("/", "welcome");
function welcome(){
    global $twig;
    if(User::is_login()){
        return $twig->render("home.html",
                    array(
                        "html_class"=>"welcome"
                    ));
    }else{
        return $twig->render('home.html',
                      array(
                        "html_class"=>"welcome",
                        "title"=>Vecni::$BRAND_NAME
                      )
                  );
    }
}

/**
* Sign in page for users
*/
Vecni::set_route("/signin", "signin_require");
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
Vecni::set_route("/registration", "reg_request");
function reg_request($message=""){
    global $twig;
    if(User::is_login()){
        Vecni::redirect();
    }
    return $twig->render('registration.php');
}

/**
* Sign in processing for users
*/
Vecni::set_route("/procsignin", "process_login");
function process_login(){
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $status = User::login($email, $pass);
        if($status){
            return Response::json_response(200, $email);
        }else{
            return Response::abort("$email, does not exists in our system. Please register for account if you don't have one");
        }
    }
}


/**
* Registration processing for users
*/
Vecni::set_route("/procregister", "register");
function register(){
    global $user;
    if($first_name = Request::POST('first_name') && $last_name =  Request::POST('last_name') && $password = Request::POST('password') && $email = Request::POST('email')){
        $new_user = new User();
        $new_user->first_name = $first_name;
        $new_user->last_name = $last_name;
        $new_user->email = $email;
        if($dob = Request::POST('dob')){
            $new_user->dob  = DateTime::createFromFormat('m/d/Y',
                                           $dob);
        }else{
            $new_user->dob = new DateTime("NOW");
        }
        $new_user->gender = Request::POST('gender');
        if($school = Request::POST('school')){
            $new_user->school = $school;
        }
        $status = $new_user->register($email, $password);
        if($status){
            return Response::json_response(200, $email);
        }else{
            return Response::abort("$email, does not exists in our system. Please register for account if you don't have one");
        }
    }
}

Vecni::set_route("/facebooklogin", "login_with_social_network");
Vecni::set_route("/googleplus", "login_with_social_network");
Vecni::set_route("/twitter", "login_with_social_network");
function login_with_social_network(){
    global $user;
    if(User::is_login()){
        Vecni::redirect();
    }
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['social_network']) && !empty($_POST['social_network_id']) && !empty($_POST['email'])){
        $new_user = new User();
        $new_user->first_name = $_POST['first_name'];
        $new_user->last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $new_user->dob  = DateTime::createFromFormat('m/d/Y',
                                           $_POST['dob']);
        $new_user->gender = $_POST['gender'];
        if(!empty($_POST['school'])){
            $new_user->school = $_POST['school'];
        }
        $account_type = $_POST['social_network'];
        $account_id = $_POST['social_network_id'];
        $status = $new_user->login_with_social_network($email, $account_type, $account_id);
        if($status){
            return Response::json_response(200, $email);
        }else{
            return Response::json_response(204, "Something went wrong");
        }
    }
}


/**
* Log out users out of Tattle Tale
* @redirect page welcome
*/
Vecni::set_route("/logout", "log_out");
function log_out(){
    global $twig;
    if(User::is_login()){
        User::log_out();
        $twig->addGlobal("user", new User());
    }
    Vecni::redirect();
}

?>


